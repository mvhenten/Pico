<?php
class Controller_Admin_Image extends Pico_AdminController{
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

        return $this->getView()->linkList( $links );
    }


    public function listAction(){
        $request = $this->getRequest();

        if( $request->isPost() && $post = $request->getPost() ){
            if( $post->action ){
                $this->_redirect( $this->getView()->Url(array(
                    'action'    => $post->action,
                    'id'        => join( ',', array_keys( $post->item ) )
                )));
            }

            $this->_forward('order');
        }

        if( is_numeric( $request->id ) ){
            $images = Nano_Db_Query::get('ImageLabel')
                    ->leftJoin( 'item', 'id', 'image_id')
                    ->where( 'label_id', $request->id)
                    ->where( 'item.id !=', 'NULL' )
                    ->order('priority')
                    ->setModel( new Model_Image() );

            $this->getView()->headScript()->append(null,'$(function(){'
                . '$(".item-priority").hide(); '
                . '$("#image-list").sortable({update:function(evt, ui){'
                . '$(".item-priority").each(function(i,el){el.value = i});'
                . '}})})');
        }
        else{
            $images = Nano_Db_Query::get('Image')->order('-inserted');
        }

        if( $images->count() == 0 ){
            $this->_redirect( $this->getView()->url( array('action'=>'add', 'id'=>null)) );
        }


        //$form = new Form_ListImages( $images );
        $this->getview()->content = $this->getView()->ImageList( $images, array(
            'delete' => 'delete images', 'labels' => 'edit labels'));

        $html = array();
        $html[] = '<h2>Images</h2>&nbsp;';
        $html[] = $this->getView()->Link( 'upload image',
            array('action' => 'add', 'id' => null), array( 'class' => 'button' ));

        $this->getView()->actions = join( "\n", $html );
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
        $this->_redirect($this->getView()->url(array(
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

        $this->getView()->content = $form;
    }

    protected function addAction(){
        //$this->getView()->headScript()->append( '/js/upload.js');
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
        $html[] = $this->getView()->Link( 'upload image',
            array('action' => 'add', 'id' => null), array( 'class' => 'button' ));
        $html[] = '&nbsp;';
        $html[] = $this->getView()->Link( 'list images',
            array('action' => 'list', 'id' => null), array('class' => 'button'));

        $this->getView()->actions = join( "\n", $html );
    }

    //protected  function editAction(){
    //    $request = $this->getRequest();
    //    $image = Model_Image::get( $request->id );
    //    $html  = array();
    //
    //    $form = new Form_EditItem( $image );
    //
    //    if( $request->isPost() ){
    //        $post = $request->getPost();
    //        $form->validate( $post );
    //
    //        if( ! $form->hasErrors() ){
    //            if( $post->delete ){
    //                $this->_redirect('/admin/image/delete/' . $image->id );
    //            }
    //
    //            $image->name        = $post->name;
    //            $image->description = $post->description;
    //            $image->visible     = (bool) $post->visible;
    //
    //            $image->put();
    //            $this->_redirect( '/admin/image/edit/' . $request->id );
    //        }
    //    }
    //
    //
    //    $this->getView()->content = $form;
    //    if( $request->id ){
    //        $img = new Nano_Element( 'img', array('src' => $this->getView()->url(array(
    //                'module' => null,
    //                'action' => 'vignette',
    //                'controller' => 'image',
    //                'id'   => $request->id
    //            )),
    //            'width' => 200
    //        ));
    //        $form->addChild($img);
    //    }
    //
    //    $html[] = sprintf('<h2>Editing <em>%s</em></h2>&nbsp;', $image->name);
    //    $html[] = $this->getView()->Link( 'upload image',
    //        array('action' => 'add', 'id' => null), array( 'class' => 'button' ));
    //    $html[] = '&nbsp;';
    //    $html[] = $this->getView()->Link( 'list images',
    //        array('action' => 'list', 'id' => null), array('class' => 'button'));
    //
    //    $this->getView()->actions = join( "\n", $html );
    //}
}
