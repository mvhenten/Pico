<?php
error_reporting(E_ALL | E_STRICT);
class Pico_View_Admin_Image extends Pico_View_Admin_Base{
    /**
     * Handler for /admin/image/upload
     * Stores a new item and image_data
     */
    public function upload( $request, $config ){
        if( ! $request->isPost() ){
            return $this->template()->render( 'image/upload');
        }

        $file = (object) $_FILES['image'];

        if( $file->error ){
            $this->response()->redirect('/admin/image?error=' . $file->error );
        }

        $post = $request->getPost();

        if( null !== ($info = Nano_Gd::getInfo( $file->tmp_name ))){
            $item = $this->model('Item', array(
                'name'     => $file->name,
                'visible'   => 0,
                'type'      => 'image',
                'inserted'  => date('Y-m-d H:i:s'),
                'slug'      => $request->slug($file->name)
            ))->store();

            $this->_storeImageData( $item, $file );
        }

        $this->response()->redirect('/admin/image' );
        exit;
    }

    private function _storeImageData( $item, $file ){
        $gd  = new Nano_Gd( $file->tmp_name );

        list( $width, $height ) = array_values( $gd->getDimensions() );
        $exif = new Nano_Exif( $file->tmp_name );

        $gd   = $this->_rotateImageData( $exif, $gd );
        $src  = ($info[2] == IMAGETYPE_PNG) ?  $gd->getImagePNG() : $gd->getImageJPEG();
        $type = ($info[2] != IMAGETYPE_PNG) ? 'image/jpeg' : $file->type;

        $this->model('ImageData', array(
            'image_id'  => $item->id,
            'size'      => $file->size,
            'mime'      => $type,
            'width'     => $width,
            'height'    => $height,
            'data'      => $src,
            'filename'  => $file->name,
            'type'      => 'original'
        ))->store();
    }

    private function _rotateImageData( Nano_Exif $exif, Nano_Gd $gd ){
        switch( $exif->orientation() ){
            case 2:
                return $gd->flipHorizontal();
            case 3:
                return $gd->rotate( 180 );
            case 4:
                return $gd->flipVertical();
            case 5:
                return $gd->flipVertical()->rotate(90);
            case 6:
                return $gd->rotate( -90 );
            case 7:
                return $gd->flipHorizontal()->rotate( -90 );
            case 8:
                return $gd->rotate( 90 );
        }

        return $gd;
    }

    public function postLabel( $request, $config ){
        return $this->postEdit( $request, $config );
    }

    public function postEdit( $request, $config ){
        $post = $request->getPost();

        $item = $this->model('Item', $request->id );

        if( null === $item->id ){
            throw new Exception('Invalid ID');
        }

        $labels = array_keys((array) $post->labels);

        $this->model('ImageLabel')->delete(array(
            'image_id' => $request->id
        ));

        foreach( $labels as $id ){
            $n = $this->model('ImageLabel', array(
                'image_id' => $request->id,
                'label_id' => $id,
                'priority' => 0
            ))->store();
        }

        if( preg_match( '/^untitled/',  $post->slug ) ){
            $item->slug = $request->slug($post->name);
        }
        else{
            $item->slug = $post->slug;
        }

        $item->name         = $post->name;
        $item->description  = $post->description;
        $item->visible      = (bool) $post->visible;
        $item->store(array( id => $request->id ) );

        $this->response()
            ->redirect( '/admin/image/' . $request->action . '/' . $request->id );
    }

    protected  function getEdit(  $request, $config ){
        $template = $this->template();

        $image           = new Pico_Model_Item( $request->id );
        $labels          = $this->model('Item')->search(array('type' => 'label'));
        $labels_selected = $image->labels();

        $labels_selected->setFetchMode( PDO::FETCH_COLUMN, 0 );
        $labels_selected_ids = $labels_selected->fetchAll();

        $form = new Form_Item( $image );

        $fieldset = array(
            'type'  => 'fieldset',
            'elements'=>array(),
            'label' => 'labels'
                .' <a onclick="$(this.parentNode.parentNode).find(\'input\').attr(\'checked\', true)" href="#all">(select all)</a>'
                .' <a onclick="$(this.parentNode.parentNode).find(\'input\').attr(\'checked\',0)" href="#none">(select none)</a>'
        );

        foreach( $labels as $label ){
            $fieldset['elements']['labels[' . $label->id . ']'] = array(
                'type'  => 'checkbox',
                'value' => (bool) in_array( $label->id, $labels_selected_ids),
                'label' => $label->name
            );
        }

        // append fieldset and move the submit button...
        $block = current($form->children(array('id'=> 'item-values')));
        $block->addElement('fieldset-labels', $fieldset );

        $submit = current($form->removeChildren(array('name'=>'save-changes')));
        $block->addChild($submit);

        //$form->addChild($submit);

        $template->image = $image;
        $template->form = $form;
        $template->render('image/edit');
        return $template;
    }

    public function postOrder( $request, $config ){
        $post = $request->getPost();

        $label = $this->model('Item', $request->id);

        if( ! $label->id ){
            throw new Exception('Not a valid label id');
        }

        $this->model('ImageLabel')->delete(array(
            'image_id' => array_keys($post->priority),
            'label_id' => $label->id
        ));

        print_r( array(
            'image_id' => array_keys($post->priority),
            'label_id' => $label->id
        ));

        foreach( $post->priority as $id => $priority ){
            printf( '%s => %s' . "\n", $id, $label->id );
            $this->model('ImageLabel', array(
                'label_id' => $request->id,
                'image_id' => $id,
                'priority' => $priority
            ))->store();
        }

        $this->response()
            ->redirect( '/admin/image/list/' . $request->id );
    }

    public function postList( $request, $config ){
        $post = $request->getPost();

        if( $post->form_action_labels ){
            return $this->postLabelsbulk( $request, $config );
        }

    }

    public function postLabelsbulk( $request, $config ){
        $post = $request->getPost();

        if( $post->apply ){
            $images = json_decode($post->images);
            $this->model('ImageLabel')->delete(array('image_id' => $images));


            foreach( $post->labels as $label_id => $bool ){
                foreach( $images as $id ){
                    $this->model('ImageLabel', array(
                        'image_id'  => $id,
                        'label_id'  => $label_id
                    ))->store();
                }
            }
        }
        else{
            $selected_query = $this->model('ImageLabel')->search(array(
                'where' => array( 'image_id' => $post->image ),
                'group' => 'label_id'
            ));

            $selected_query->setFetchMode( PDO::FETCH_COLUMN, 1 );
            $selected_labels = $selected_query->fetchAll();

            $labels = array();
            foreach( $this->model('Item')->search( array( 'where' => array('type' => 'label') ) ) as $label ){
                $labels[$label->id] = (object) array(
                    'selected'  => in_array( $label->id, $selected_labels ),
                    'name'      => $label->name,
                    'id'        => $label->id
                );
            }

            $this->template()->labels = $labels;
            $this->template()->images = json_encode(array_keys($post->image));
            return $this->template()->render( 'image/bulk');
        }

        $this->response()
            ->redirect( '/admin/image/list/' . $request->id );

    }


    public function getList( $request, $config ){
        $values = array();

        if( is_numeric( $request->id ) ){
            $label = $this->model('Item', $request->id);
            $pager = $label->pager('images');
        }
        else{
            $where = array('type' => 'image','order' => '-inserted');
            $pager  = $this->model('Item')->pager('search', $where );
        }

        $labels = $this->model('Item')->search( array('type' => 'label') );

        $template = $this->template();

        $template->labels = $labels;
        $template->images = $pager->getPage($request->page);
        $template->pager  = $pager;

        return $template->render( 'image/list');
    }


    public function getLabel( $request, $config ){

        if( $request->id ){
            $label = $this->model('Item', $request->id );

            $html  = array();

            $form = new Form_Item( $label );
            $this->template()->label = $label;
            $this->template()->form = $form;

            $tpl_path = 'image/label';
        }
        else{
            $labels = $this->model('Item')->search(array(
                'where' => array('type' => 'label'),
                'order' => 'updated'
            ));

            $this->template()->labels = $labels;
            $tpl_path = 'image/labels';
        }

        return $this->template()->render($tpl_path);
    }
}
