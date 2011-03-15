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
                $this->response()->redirect('/admin/image/' . $request->action . '/' . $image->id );
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


            foreach( $post->content as $id => $content ){
                $item = new Model_ItemContent($id);

                if( isset($content['delete']) ){
                    $item->delete();
                }
                else{
                    if( isset($content['draft']) ){
                        $item->draft = $content['value'];
                    }
                    else{
                        $item->value = $content['value'];
                    }
                    $item->put();
                }

            }


            $this->response()
                ->redirect( '/admin/image/' . $request->action . '/' . $request->id );
        }
        else if( $request->action == 'delete' ){
            return $this->getDelete( $request, $config );
        }
        else if( $request->action == 'list' ){
            
        }
    }

    public function getUpload( $request, $config ){
        $template = $this->template();
        return $this->template()->render('admin/template/image/upload');
    }

    public function getDelete( $request, $config ){
        if( $request->id ){
            $item = new Model_Item($request->id);

            if( $request->isPost() ){
                $post = $request->getPost();
                $type = $item->type;

                if( $post->confirm ){
                    $item->delete();
                }

                $type = $type == 'label' ? 'labels' : 'list';


                $this->response()->redirect('/admin/image/' . $type );
            }
            $this->template()->item = $item;
            return $this->template()->render('admin/template/image/delete');
        }
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

    public function getLabels( $request, $config ){
        $label = Nano_Db_Query::get('Item')->where('type', 'label')->order('-inserted');
        $this->template()->labels = $label;

        return $this->template()->render('admin/template/image/labels');
    }

    public function getLabel( $request, $config ){
        $template = $this->template();

        if( null == $request->id ){
            $label = new Model_Item(array(
                'name'        => 'New Label',
                'description' => 'Description for a new label',
                'type'        => 'label'
            ));
            $label->put();
            $this->response()->redirect( '/admin/image/labels' );
        }

        $label = new Model_Item( $request->id );
        $html  = array();

        $form = new Form_EditItem( $label );
        $template->label = $label;
        $template->form = $form;

        $template->render( 'admin/template/image/label');
        return $template;
    }

    protected  function getEdit(  $request, $config ){
        $template = $this->template();

        $image = new Model_Image( $request->id );
        $form = new Form_EditItem( $image );

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

        $form->addChild($submit);

        $template->image = $image;
        $template->form = $form;

        $template->render( 'admin/template/image/edit');
        return $template;
    }

    public function getContent( $request, $config ){
        $content = new Model_ItemContent();
        $content->item_id = $request->id;
        $content->put();

        $item = new Model_Item( $request->id );

        if( $item->type == 'label' ){
            $this->response()->redirect( '/admin/image/label/' . $request->id );
        }

        $this->response()->redirect( '/admin/image/edit/' . $request->id );
    }

    //
    //protected function orderAction(){
    //    $request = $this->getRequest();
    //    $post = $request->getPost();
    //    $delete_filter = array();
    //
    //
    //    foreach( $post->priority as $id => $value ){
    //        $delete_filter[] = array(
    //            array('image_id', $id),
    //            array('label_id', $request->id)
    //        );
    //    }
    //
    //    Nano_Db_Query::get('ImageLabel')->delete( $delete_filter );
    //
    //    foreach( $post->priority as $id => $value ){
    //        $model = new Model_ImageLabel();
    //        $model->image_id = $id;
    //        $model->label_id = $request->id;
    //        $model->priority = intval($value);
    //        $model->put();
    //    }
    //    $this->_redirect($this->template()->url(array(
    //        'action'    => 'list',
    //        'id'        => $request->id
    //    )));
    //}

    //protected function labelsAction(){
    //    $request = $this->getRequest();
    //    $elements = array();
    //
    //    $images = explode( ',', urldecode($request->id));
    //    $labels = Nano_Db_Query::get('Label');
    //    $filter = Nano_Db_Query::get('ImageLabel');
    //
    //    foreach( $labels as $label ){
    //        foreach( $images as $id ){
    //            $filter->orWhere(array(array( 'image_id', $id ), array('label_id', $label->id)));
    //        }
    //    }
    //
    //    $selected = array(); foreach( $filter as $f ) $selected[] = $f->label_id;
    //    $selected = array_unique($selected);
    //    $form = new Form_EditLabels( $labels, $selected );
    //
    //    if( $request->isPost() && $post = $request->getPost() ){
    //        $label_ids = array_keys( (array) $post->selection);
    //
    //        if( count( $filter ) > 0 ){
    //            $delete_filter = array();
    //
    //            foreach( $filter as $comb ){
    //                $delete_filter[] = array(
    //                    array('image_id', $comb->image_id),
    //                    array('label_id', $comb->label_id)
    //                );
    //            }
    //
    //            Nano_Db_Query::get('ImageLabel')->delete( $delete_filter );
    //        }
    //
    //        if( count( $label_ids ) > 0 ){
    //            foreach( $images as $id ){
    //                foreach( $label_ids as $label_id ){
    //                    $model = new Model_ImageLabel();
    //                    $model->image_id = $id;
    //                    $model->label_id = $label_id;
    //                    $model->put();
    //                }
    //            }
    //        }
    //        $this->_redirect( '/admin/image' );
    //    }
    //
    //    $this->template()->content = $form;
    //}
    //

}
