<?php
class Controller_Admin_Image extends Pico_AdminController{
    const IMAGESIZE_THUMBNAIL = '96x96';
    const IMAGESIZE_ICON      = '32x32';
    const IMAGESIZE_VIGNETTE   = '400x300';

    const TYPE_ORIGINAL       = 1;
    const TYPE_VIGNETTE       = 2;
    const TYPE_THUMBNAIL      = 3;
    const TYPE_ICON           = 4;
    const TYPE_CUSTOM         = 5;

	public function listAction(){
        $request = $this->getRequest();
        $labelId = $request->id;

        if( null !== $request->id ){
            $search = new Model_ImageLabel();
            $search->setOffset( $request->offset );

            //$label = new Model_Label( array( 'id' => $request->id ) );
            $images = $search->search( array('label_id' => $request->id ) );


            if( count( $images ) == 0 ){
                $this->getView()->mainLeft = sprintf('No images with label %s found!', $label->name );
                return;
            }
        }
        else{
            $images = Model_Image::get()->all();
            //$images = ( $image = new Model_Image() ) ? $image->search():null;
        }

        $form = $this->_helper('imageList', $images );

        if( $request->isPost() && $post = $request->getPost() ){
            $form->validate( $post );
            if( ! $form->hasErrors() ){
                if( $post->action == 'labels' && count($post->selection) > 0 ){
                    $query = join(',' ,array_keys( $post->selection ) );
                    $this->_redirect( '/admin/image/labels/' . $query );
                }
            }
        }

        $this->getview()->mainLeft = $form;
	}

    protected function labelsAction(){
        $request = $this->getRequest();
        $elements = array();

        $images = explode( ',', urldecode($request->id));
        $rows = ($rows = new Model_ImageLabel()) ? $rows->search( array('image_id' => $images ) ):null;
        $labels   = ( $labels = new Model_Label(array('type'=>self::ITEM_TYPE_LABEL)) ) ? $labels->search():null;
        $selected = array(); foreach( $rows as $val) $selected[]=$val->label_id;

        $form = new Form_EditLabels( $labels, $selected );

        if( $request->isPost() && $post = $request->getPost() ){
            $selection = array_keys( (array) $post->selection );

            foreach( $images as $image ){
                $image = new Model_Image( array('id'=>$image) );
                $image->setLabels( $selection );
            }
            $this->_redirect( '/admin/image' );
        }

        $this->getView()->mainLeft = $form;
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
                    'imageId'   => $image->id,
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

        $this->getview()->mainLeft = (string) $form;
    }

    protected  function editAction(){
        $request = $this->getRequest();
        //$image = new Model_Image();
        //
        //$image->type = self::ITEM_TYPE_IMAGE;
        //
        //if( null !== $request->id ){
        //    $image->id = $request->id;
        //}
        //
        //$labels = ( $labels = new Model_Label(array('type'=>self::ITEM_TYPE_LABEL)) ) ? $labels->search():null;
        //
        //$ids = array(); foreach( $image->fetchLabels() as $val) $ids[]=$val->id;
        //$elements = array(new Nano_Element('img', array('src'=>'/admin/image/view/thumbnail/1')));
        //
        //foreach( $labels as $label ){
        //    $elements['labels[' . $label->id . ']'] = array(
        //        'type'  => 'checkbox',
        //        'label' => $label->name,
        //        'value' => in_array( $label->id, $ids )
        //    );
        //}
        //
        
        $image = Model_Image::get( $request->id );
        $form = $this->_helper( 'ItemForm', $image, array() );
        
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
        
                //$image->data = $post->data;
        
                //$image->setLabels( array_keys( (array) $post->labels ) );
                $image->put();
                
                
        
                $this->_redirect( '/admin/image/edit/' . $request->id );
            }
        }
        

        $this->getview()->mainLeft = $form;
    }

    //public function postDispatch(){
    //    $this->getView()->actions = $this->getMenu();
    //}

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
