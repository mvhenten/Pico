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
                $this->response()->redirect('/admin/image/edit/' . $image->id );
            }
        }
        else if( $request->action == 'edit' || $request->action == 'label' ){
            $item = new Model_Item( $request->id );
            $item->name = $post->name;
            $item->description = $post->description;
            $item->visible = (bool)$post->visible;
            $item->put();

            //var_dump($post);
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

            $to = $item->type == Model_Item::P_ITEM_TYPE_LABEL ? 'label' : 'edit';

            $this->response()
                ->redirect( '/admin/image/' . $to . '/' . $request->id );
        }
    }

    public function getUpload( $request, $config ){
        $template = $this->template();
        return $this->template()->render('admin/template/image/upload');
    }

    public function getList( $request, $config ){
        $template = $this->template();
        $values = array();

        if( is_numeric( $request->id ) ){
            $label = new Model_Label( $request->id );
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

        $this->template()->images = $images;
//        var_dump($images);
        return $this->template()->render('admin/template/image/list');
    }

    public function getLabels( $request, $config ){
        $label = Nano_Db_Query::get('Item')->where('type', 'label')->order('-inserted');
        $this->template()->labels = $label;

        return $this->template()->render('admin/template/image/labels');
    }

    public function getLabel( $request, $config ){
        $template = $this->template();

        if( null == $request->id ){

            $label = new Model_Item();
            var_dump($label);
            $label->name = "New Label";
            $label->description = "Description for New Label";
            $label->put();

//            $this->_redirect( '/admin/image/labels' );
//            $this->_redirect( '/admin/image/edit/' . $request->id );
            exit();
        }

        $label = new Model_Item( $request->id );
        $html  = array();

        $form = new Form_EditItem( $label );
        $template->image = $label;
        $template->form = $form;

        $template->render( 'admin/template/image/label');
        return $template;
    }

    public function getAddlabel( $request, $config ){
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


    //public function get(){
    //
    //}

/*
    protected function getMenu(){
        $links = array(
            array(
                'target' => array('action' => 'list', 'id' => null),
                'value'  => 'all images'
            ),
            array(
                'target' => array('action' => 'list', 'id' => 'recent'),
                'value'  => 'most recent uploads'
            )
        );

        $items = Nano_Db_Query::get('ImageLabel')
                ->leftJoin( 'item', 'id', 'label_id')
                ->where('id !=', 'NULL', 'item')
                ->group('label_id')
                ->setModel( new Model_Label() );

        foreach( $items as $item ){
            $links[] = array(
                'target'=> array( 'action' => 'list', 'id' => $item->id),
                'value'  => $item->name,
            );
        }

        return $this->template()->linkList( $links );
    }


    public function listAction(){
    }

    protected function orderAction(){
        $request = $this->getRequest();
        $post = $request->getPost();
        $delete_filter = array();


        foreach( $post->priority as $id => $value ){
            $delete_filter[] = array(
                array('image_id', $id),
                array('label_id', $request->id)
            );
        }

        Nano_Db_Query::get('ImageLabel')->delete( $delete_filter );

        foreach( $post->priority as $id => $value ){
            $model = new Model_ImageLabel();
            $model->image_id = $id;
            $model->label_id = $request->id;
            $model->priority = intval($value);
            $model->put();
        }
        $this->_redirect($this->template()->url(array(
            'action'    => 'list',
            'id'        => $request->id
        )));
    }

    protected function labelsAction(){
        $request = $this->getRequest();
        $elements = array();

        $images = explode( ',', urldecode($request->id));
        $labels = Nano_Db_Query::get('Label');
        $filter = Nano_Db_Query::get('ImageLabel');

        foreach( $labels as $label ){
            foreach( $images as $id ){
                $filter->orWhere(array(array( 'image_id', $id ), array('label_id', $label->id)));
            }
        }

        $selected = array(); foreach( $filter as $f ) $selected[] = $f->label_id;
        $selected = array_unique($selected);
        $form = new Form_EditLabels( $labels, $selected );

        if( $request->isPost() && $post = $request->getPost() ){
            $label_ids = array_keys( (array) $post->selection);

            if( count( $filter ) > 0 ){
                $delete_filter = array();

                foreach( $filter as $comb ){
                    $delete_filter[] = array(
                        array('image_id', $comb->image_id),
                        array('label_id', $comb->label_id)
                    );
                }

                Nano_Db_Query::get('ImageLabel')->delete( $delete_filter );
            }

            if( count( $label_ids ) > 0 ){
                foreach( $images as $id ){
                    foreach( $label_ids as $label_id ){
                        $model = new Model_ImageLabel();
                        $model->image_id = $id;
                        $model->label_id = $label_id;
                        $model->put();
                    }
                }
            }
            $this->_redirect( '/admin/image' );
        }

        $this->template()->content = $form;
    }

    protected function addAction(){
        //$this->template()->headScript()->append( '/js/upload.js');
        $request = $this->getRequest();
        $errors  = array();

        $form = new Form_AddImage();

        if( $request->isPost() && is_uploaded_file( $_FILES['image']['tmp_name'] ) ){
            $file = (object) $_FILES['image'];

            if( null !== ($info = Nano_Gd::getInfo( $file->tmp_name ))){
                $gd  = new Nano_Gd( $file->tmp_name );
                list( $width, $height ) = array_values( $gd->getDimensions() );

                $image = new Model_Image(array(
                    'name'     => $file->name,
                    'visible'   => 0,
                    'type'      => 1,
                    'inserted'  => date('Y-m-d H:i:s')
                ));

                $image->put();

                if( $info[2] === IMAGETYPE_PNG ){
                    $src = $gd->getImagePNG();
                }
                else{
                    $src = $gd->getImageJPEG();
                    $file->type = 'image/jpeg';
                }

                $data = new Model_ImageData(array(
                    'image_id'   => $image->id,
                    'size'      => $file->size,
                    'mime'      => $file->type,
                    'width'     => $width,
                    'height'    => $height,
                    'data'      => $src,
                    'filename'  => $file->name,
                    'type'      => 1
                ));

                $data->put();

                $this->_redirect( '/admin/image/edit/' . $image->id );
            }
            else{
                $errors[] = 'Not a valid image type: ' . $file->name;
            }
        }
        else{
            $errors[] = 'Tampering detected: not an uploaded file';
        }

        $image = new Model_Image();

        $this->getview()->content = (string) $form;
        $html = array();
        $html[] = '<h2>Upload image</h2>';
        $html[] = $this->template()->Link( 'upload image',
            array('action' => 'add', 'id' => null), array( 'class' => 'button' ));
        $html[] = '&nbsp;';
        $html[] = $this->template()->Link( 'list images',
            array('action' => 'list', 'id' => null), array('class' => 'button'));

        $this->template()->actions = join( "\n", $html );
    }
*/

}
