<?php
class Admin_View_Image extends Admin_View_Base{
    private function _procesUpload(){
        $file = (object) $_FILES['image'];

        if( null !== ($info = Nano_Gd::getInfo( $file->tmp_name ))){
            $gd  = new Nano_Gd( $file->tmp_name );
            list( $width, $height ) = array_values( $gd->getDimensions() );

            $image = new Pico_Model_Item(array(
                'name'     => $file->name,
                'visible'   => 0,
                'type'      => 'image',
                'inserted'  => date('Y-m-d H:i:s'),
                'slug'      => $file->name
            ));

            $image->store();

            return;

            if( $info[2] == IMAGETYPE_PNG ){
                $src = $gd->getImagePNG();
            }
            else{
                $src = $gd->getImageJPEG();
                $file->type = 'image/jpeg';
            }

            $data = new Pico_Model_ImageData(array(
                'image_id'  => $image->id,
                'size'      => $file->size,
                'mime'      => $file->type,
                'width'     => $width,
                'height'    => $height,
                'data'      => $src,
                'filename'  => $file->name,
                'type'      => 'original'
            ));

            $data->store();
        }
    }


    public function post( $request, $config ){
        $post = $request->getPost();

        if( $request->action == 'upload' ){
            $this->_procesUpload();
            $this->response()->redirect('/admin/image' );
        }
        else if( $request->action == 'edit' || $request->action == 'label' ){
            
            
            if( $request->action == 'edit' ){
                $item = new Model_Image($request->id);
                $labels = array_keys((array) $post->labels);
                $item->setLabels($labels);
            }
            else{
                $item = new Model_Item( $request->id );
            }
            
            if( preg_match( '/^unttiled',  $post->slug ) ){
                $item->slug = $request->slug($post->name);
            }

            if( stripos( $item->slug,'untitled' ) === 0 ){
                
            }
            else{
                $item->slug = $request->slug($post->slug);
            }



            $item->name = $post->name;
            $item->description = $post->description;
            $item->visible = (bool)$post->visible;
            $item->put();

            $this->response()
                ->redirect( '/admin/image/' . $request->action . '/' . $request->id );
        }
        else if( $request->action == 'list' ){
            return $this->getLabelsBulk( $request, $config );
        }
        else if( $request->action == 'labelsbulk' ){
            $post = $request->getPost();
            
            if( $post->apply ){
                $images = json_decode($post->images);
                error_log( print_r( $images, true ));
                $this->model('ImageLabel')->delete(array('image_id' => $images));
                error_log(print_r($post->labels, true));
                
                foreach( $post->labels as $label_id => $bool ){
                    foreach( $images as $id ){
                        $this->model('ImageLabel', array(
                            'image_id'  => $id,
                            'label_id'  => $label_id
                        ))->store();                        
                    }
                }
            }

            $this->response()
                ->redirect( '/admin/image/list/' . $request->id );
        }
        else if( $request->action == 'order' ){
            $this->model('ImageLabel')->delete(array(
                'image_id' => array_keys($post->priority)));

            foreach( $post->priority as $id => $priority ){
                $this->model('ImageLabel', array(
                    label_id => $request->id,
                    image_id => $id,
                    priority => $priority
                ))->store();

                $img_label->store();
            }

            $this->response()
                ->redirect( '/admin/image/list/' . $request->id );
        }
    }


    public function getUpload( $request, $config ){
        return $this->template()->render( APPLICATION_ROOT . '/Admin/template/image/upload');
    }

    public function getLabelsBulk( $request, $config ){
        if( ! $request->isPost() )
            return;

        $post = $request->getPost();        
        
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
        return $this->template()->render( APPLICATION_ROOT . '/Admin/template/image/bulk');
    }

    public function getList( $request, $config ){
        $template = $this->template();
        $values = array();

        if( is_numeric( $request->id ) ){
            $label = new Pico_Model_Item( $request->id );
            $images = $label->images()->fetchAll();
        }
        else{
            $images = $this->model('Item')->search(array(
                'where' => array('type' => 'image')));
        }

        $labels = $this->model('Item')->search(array(
            'where' => array('type' => 'label')));

        $template->labels = $labels;
        $template->images = $images;

        return $template->render( APPLICATION_ROOT . '/Admin/template/image/list');
    }


    public function getLabel( $request, $config ){        
        $template = '/Admin/template/image/label';

        if( $request->id ){
            $item = new Pico_Model_Item( $request->id );

            $html  = array();

            $form = new Form_Item( $label );
            $this->template()->label = $label;
            $this->template()->form = $form;            
        }
        else{
            $item = new Pico_Model_Item();
            $labels = $item->search(array(
                'where' => array('type' => 'label'),
                'order' => 'updated'
            ));
            
            $this->template()->labels = $labels;
            $template = '/Admin/template/image/labels';

        }
        
        return $this->template()->render( APPLICATION_ROOT . $template );
    }

    protected  function getEdit(  $request, $config ){
        $template = $this->template();

        $image = new Model_Image( $request->id );
        $form = new Form_Item( $image );

        $fieldset = array(
            'type'  => 'fieldset',
            'elements'=>array(),
            'label' => 'labels'
                .' <a onclick="$(this.parentNode.parentNode).find(\'input\').attr(\'checked\', true)" href="#all">(select all)</a>'
                .' <a onclick="$(this.parentNode.parentNode).find(\'input\').attr(\'checked\',0)" href="#none">(select none)</a>'
        );
        foreach( $image->labels() as $label ){
            $fieldset['elements']['labels[' . $label->id . ']'] = array(
                'type'  => 'checkbox',
                'value' => (bool) $label->image_id,
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

        $template->render( APPLICATION_ROOT . '/Admin/template/image/edit');
        return $template;
    }
}
