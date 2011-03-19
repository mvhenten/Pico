<?php
class Controller_Admin_Image extends Nano_Controller{
    public function post( $request, $config ){
        $post = $request->getPost();

        if( $request->action == 'upload' ){
            $file = (object) $_FILES['image'];


            if( null !== ($info = Nano_Gd::getInfo( $file->tmp_name ))){
                $gd  = new Nano_Gd( $file->tmp_name );
                list( $width, $height ) = array_values( $gd->getDimensions() );

                $image = new Model_Item(array(
                    'name'     => $file->name,
                    'visible'   => 0,
                    'type'      => 'image',
                    'inserted'  => date('Y-m-d H:i:s')
                ));

                $image->put();

                if( $info[2] == IMAGETYPE_PNG ){
                    $src = $gd->getImagePNG();
                }
                else{
                    $src = $gd->getImageJPEG();
                    $file->type = 'image/jpeg';
                }

                $data = new Model_ImageData(array(
                    'image_id'  => $image->id,
                    'size'      => $file->size,
                    'mime'      => $file->type,
                    'width'     => $width,
                    'height'    => $height,
                    'data'      => $src,
                    'filename'  => $file->name,
                    'type'      => 1
                ));

                $data->put();
                var_dump($image);
                $this->response()->redirect('/admin/image' );
            }
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
            $images = json_decode($post->images);

            $ids = array();
            foreach($images as $id ) $ids[] = array('image_id', $id );

            Nano_Db_Query::get('ImageLabel')->delete($ids);

            foreach( $post->labels as $id => $value ){
                foreach( $images as $img_id ){
                    $insert = new Model_ImageLabel(array(
                        'label_id' => $id,
                        'image_id' => $img_id
                    ));
                    $insert->put();
                }
            }

            $this->response()
                ->redirect( '/admin/image/list/' . $request->id );
        }
        else if( $request->action == 'order' ){
            $ids = array();
            foreach($post->priority as $id => $p ) $ids[] = array('image_id', $id );

            Nano_Db_Query::get('ImageLabel')->delete($ids);

            foreach( $post->priority as $id => $priority ){
                $insert = new Model_ImageLabel(array(
                    'label_id'  => $request->id,
                    'image_id'  => $id,
                    'priority'  => $priority
                ));
                $insert->put();
            }

            $this->response()
                ->redirect( '/admin/image/list/' . $request->id );
        }
    }


    public function getUpload( $request, $config ){
        return $this->template()->render('admin/template/image/upload');
    }

    public function getLabelsBulk( $request, $config ){
        if( ! $request->isPost() )
            return;

        $post = $request->getPost();

        $labels = Nano_Db_Query::get('Item')
            ->where('type', 'label');

        $selected = Nano_Db_Query::get('ImageLabel')
            ->group('label_id');

        foreach( $post->image as $id ){
            $selected->orWhere('image_id', $id );
        }

        $selected = $selected->pluck('label_id');

        $this->template()->selected = array_flip($selected);
        $this->template()->labels = $labels;
        $this->template()->images = json_encode(array_keys($post->image));
        //print "TESTA";
        return $this->template()->render('admin/template/image/bulk');
    }

    public function getList( $request, $config ){
        $template = $this->template();
        $values = array();

        if( is_numeric( $request->id ) ){
            $images = Nano_Db_Query::get('ImageLabel')
                    ->leftJoin( 'item', 'id', 'image_id')
                    ->where( 'label_id', $request->id)
                    ->where( 'item.id !=', 'NULL' )
                    ->order('priority')
                    ->setModel( new Model_Image() );
        }
        else{
            $images = Nano_Db_Query::get('Item')->where('type', 'image')->order('-inserted');
        }
        $labels = Nano_Db_Query::get('Item')->where('type', 'label')->order('name ASC');

        $template->labels = $labels;
        $template->images = $images;

        return $template->render('admin/template/image/list');
    }


    public function getLabel( $request, $config ){
        $template = $this->template();

        if( null == $request->id ){
            $label = Nano_Db_Query::get('Item')->where('type', 'label')->order('updated');
            $this->template()->labels = $label;

            return $this->template()->render('admin/template/image/labels');
        }
        else{
            $label = new Model_Item( $request->id );
            $html  = array();

            $form = new Form_Item( $label );
            $template->label = $label;
            $template->form = $form;

            return $this->template()->render('admin/template/image/label');
        }
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

        $template->render( 'admin/template/image/edit');
        return $template;
    }
}
