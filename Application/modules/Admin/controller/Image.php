<?php
class Controller_Admin_Image extends Pico_AdminController{
    //const IMAGESIZE_THUMBNAIL = '96x96';
    //const IMAGESIZE_ICON      = '32x32';
    //const IMAGESIZE_VIGNETTE   = '400x300';
    //
    //const TYPE_ORIGINAL       = 1;
    //const TYPE_VIGNETTE       = 2;
    //const TYPE_THUMBNAIL      = 3;
    //const TYPE_ICON           = 4;
    //const TYPE_CUSTOM         = 5;
    //
	public function listAction(){
        $request = $this->getRequest();
        $labelId = $request->id;

        if( null !== $labelId ){
            $images = Model_ImageLabel::get()->all()
                    ->leftJoin( 'item', 'id', 'image_id')
                    ->where( 'label_id', $labelId)
                    ->setModel( new Model_Item() );

        }
        else{
            $images = Model_Image::get()->all();
        }

        $form = new Form_ListImages( $images );

        if( $request->isPost() && $post = $request->getPost() ){
            $form->validate( $post );
            if( ! $form->hasErrors() ){
                if( $post->action == 'labels' && count($post->item) > 0 ){
                    $query = join(',' ,array_keys( $post->item ) );
                    $this->_redirect( '/admin/image/labels/' . $query );
                }
            }
        }

        //$this->getview()->actions = '<h2>List images</h2>';
        $this->getview()->content = $form;

        $html = array();
        $html[] = '<h2>List images</h2>&nbsp;';
        $html[] = $this->getView()->Link( 'upload image',
            array('action' => 'add', 'id' => null), array( 'class' => 'button' ));
        
        $this->getView()->actions = join( "\n", $html );

	}

    protected function labelsAction(){
        $request = $this->getRequest();
        $elements = array();

        $images = explode( ',', urldecode($request->id));

        $labels = Model_Label::get()->all();

        $filter = Model_ImageLabel::get()->all();

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

                Model_ImageLabel::get()->delete( $delete_filter );
            }

            if( count( $label_ids ) > 0 ){
                foreach( $images as $id ){
                    foreach( $label_ids as $label_id ){
                        $model = Model_ImageLabel::get();
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

                $id = $image->put();

                if( $info[2] === IMAGETYPE_PNG ){
                    $src = $gd->getImagePNG();
                }
                else{
                    $src = $gd->getImageJPEG();
                    $file->type = 'image/jpeg';
                }

                $data = new Model_ImageData(array(
                    'image_id'   => $id,
                    'size'      => $file->size,
                    'mime'      => $file->type,
                    'width'     => $width,
                    'height'    => $height,
                    'data'      => $src,
                    'filename'  => $file->name,
                    'type'      => 1
                ));

                $data->put();

                $this->_redirect( '/admin/image/edit/' . $id );
            }
            else{
                $errors[] = 'Not a valid image type: ' . $file->name;
            }
        }
        else{
            $errors[] = 'Tampering detected: not an uploaded file';
        }
        
        $image = Model_Image::get();

        $this->getview()->content = (string) $form;
        $html = array();
        $html[] = sprintf('<h2>Editing <em>%s</em></h2>&nbsp;', $image->name);
        $html[] = $this->getView()->Link( 'upload image',
            array('action' => 'add', 'id' => null), array( 'class' => 'button' ));
        $html[] = '&nbsp;';
        $html[] = $this->getView()->Link( 'list images',
            array('action' => 'list', 'id' => null), array('class' => 'button'));
        
        $this->getView()->actions = join( "\n", $html );
    }

    protected  function editAction(){
        $request = $this->getRequest();
        $image = Model_Image::get( $request->id );
        $html  = array();
        
        $form = new Form_EditItem( $image );      
        
        if( $request->isPost() ){
            $post = $request->getPost();
            $form->validate( $post );

            if( ! $form->hasErrors() ){
                if( $post->delete ){
                    $this->_redirect('/admin/image/delete/' . $image->id );
                }

                $image->name        = $post->name;
                $image->description = $post->description;
                $image->visible     = (bool) $post->visible;

                $image->put();
                $this->_redirect( '/admin/image/edit/' . $request->id );
            }
        }


        $this->getView()->content = $form;
        $html[] = sprintf('<h2>Editing <em>%s</em></h2>&nbsp;', $image->name);
        $html[] = $this->getView()->Link( 'upload image',
            array('action' => 'add', 'id' => null), array( 'class' => 'button' ));
        $html[] = '&nbsp;';
        $html[] = $this->getView()->Link( 'list images',
            array('action' => 'list', 'id' => null), array('class' => 'button'));
        
        $this->getView()->actions = join( "\n", $html );
    }

    protected function getMenu(){
        $menu = array();

        return $menu;

        $labels = ( $label = new Model_Label() ) ? $label->search() : null;

        foreach( $labels as $label ){
            $menu['label_' . $label->id ] = array(
              'target'  => '/admin/image/list/' . $label->id,
              'value'   => 'Label ' . $label->name
            );
        }

        return array_merge( array(
            'add'   => array(
                'target' => '/admin/image/add',
                'value'  => 'Add new image'
            )
        ), $menu);
    }
}
