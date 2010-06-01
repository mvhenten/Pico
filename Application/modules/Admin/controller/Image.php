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

        if( null !== $request->id ){
            $label = new Model_Label( array( 'id' => $request->id ) );
            $images = $label->fetchImages();
//            $images = ( $image = new Model_Image() ) ? $image-
            if( count( $images ) == 0 ){
                $this->getView()->mainLeft = sprintf('No images with label %s found!', $label->name );
                return;
            }
        }
        else{
            $images = ( $image = new Model_Image() ) ? $image->search():null;
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



        if( $request->isPost() && $post = $request->getPost() ){
            $selection = array_keys( (array) $post->selection );

            foreach( $images as $image ){
                $image = new Model_Image( array('id'=>$image) );
                $image->setLabels( $selection );
            }
            $this->_redirect( '/admin/image' );
        }


        foreach( $labels as $label ){
            $elements['selection[' . $label->id . ']'] = array(
                'type'  => 'checkbox',
                'label' => $label->name,
                'value' => in_array( $label->id, $selected )
            );
        }

        $form = new Nano_Form( 'labels-bulk', array(
            'elements' => array(
                'toolbar'   => array(
                    'type'  => 'fieldset',
                    'class' => 'toolbar',
                    'legend' => '<h5>Editing labels</h5>',
                    'elements' => array(
                        'reset' => array(
                            'wrapper' => false,
                            'type' => 'button',
                            'value' => 'clear all',
                            'onclick' => '$$(".input-checkbox").each(function(el){el.checked=false;});'
                        ),
                        'select-all' => array(
                            'wrapper' => false,
                            'type' => 'button',
                            'value' => 'select all',
                            'onclick' => '$$(".input-checkbox").each(function(el){el.checked=true;});'
                        ),
                        'save' => array(
                            'wrapper' => false,
                            'type' => 'submit',
                            'value' => 'Save changes'
                        ),
                    )
                ),
                'viewport' => array(
                    'type'  => 'fieldset',
                    'class' => 'viewport',
                    'elements' => $elements
                ),
                'save' => array(
                    'wrapper' => false,
                    'type' => 'submit',
                    'value' => 'Save changes'
                ),
            )
        ));

        $this->getView()->mainLeft = $form;
    }

    protected function addAction(){
        $request = $this->getRequest();
        $errors  = array();

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

                $image->save();

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

                $data->save();

                $this->_redirect( '/admin/image/edit/' . $image->id );
            }
            else{
                $errors[] = 'Not a valid image type: ' . $file->name;
            }
        }
        else{
            $errors[] = 'Tampering detected: not an uploaded file';
        }

        $form = new Nano_Form();


        //$form->addChild( new Nano_Element( 'script', null, ob_get_clean() ) );

        $form->addElements(array(
            'image' => array(
                'type'  => 'file',
            ),
            'upload'    => array(
                'type'  => 'submit',
                'value' => 'Submit'
            )
        ));
        $form->setAttribute( 'target', 'iframeSubmit');
        $form->setAttribute( 'action', '/admin/image/add');
        $form->setAttribute( 'id', 'image-add');
        $form->setAttribute( 'onsubmit', 'loadResults()');

        $this->getView()->headScript()->append( '/js/upload.js');
        $this->getView()->headScript()->append( '/js/test.js');

        $frame = '<iframe style="display:none;" name="iframeSubmit" id="iframeSubmit"></iframe>'."\n";
        ob_start();
        ?>
        <script>
            function loadResults(){
                var handler = function(){
                    $('iframeSubmit').stopObserving('load', handler );

                    var frame = $('iframeSubmit');

                    var doc = frame.contentWindow || frame.contentDocument;
                    if( doc.document ){
                        doc = doc.document;
                    }

//                    console.log( doc.findElementsByTagName('img') );

                    console.log( doc.baseURI.replace('edit', 'view/thumbnail') );

                    var thumb = doc.baseURI.replace('edit', 'view/thumbnail');
                    var uri   = doc.baseURI;

                    $('main-left').insert('<a href="' + uri + '"><img src="' +  thumb + '" /></a>');

  //                  console.log( frame.select('img') );


                }

                $('iframeSubmit').observe('load', handler );
//                console.log( $('image-add') );
            }
        </script>

        <?php

        $this->getview()->mainLeft = (string) $form . $frame . ob_get_clean();
    }

    protected  function editAction(){
        $request = $this->getRequest();
        $image = new Model_Image();

        $image->type = self::ITEM_TYPE_IMAGE;

        if( null !== $request->id ){
            $image->id = $request->id;
        }

        $labels = ( $labels = new Model_Label(array('type'=>self::ITEM_TYPE_LABEL)) ) ? $labels->search():null;

        $ids = array(); foreach( $image->fetchLabels() as $val) $ids[]=$val->id;
        $elements = array(new Nano_Element('img', array('src'=>'/admin/image/view/thumbnail/1')));

        foreach( $labels as $label ){
            $elements['labels[' . $label->id . ']'] = array(
                'type'  => 'checkbox',
                'label' => $label->name,
                'value' => in_array( $label->id, $ids )
            );
        }

        $form = $this->_helper( 'ItemForm', $image, $elements );

        if( $request->isPost() ){
            $post = $request->getPost();
            $form->validate( $post );

            if( ! $form->hasErrors() ){
                if( $post->delete ){
                    $this->_redirect('/admin/image/delete/' . $image->id );
                }

                $image->name = $post->name;
                $image->description = $post->description;
                $image->visible     = (bool) $post->visible;

                $image->data = $post->data;

                $image->setLabels( array_keys( (array) $post->labels ) );
                $image->save();

                $this->_redirect( '/admin/image/edit/' . $image->id );
            }
        }

        $this->getview()->mainLeft = $form;
    }

    public function viewAction(){
        $request = $this->getRequest();

        $id = $request->id;
        $type = self::TYPE_ORIGINAL;

        if( !is_numeric( $request->id ) && is_numeric( $request->value ) ){
            $id = $request->value;
            switch( $request->id ){
                case 'thumbnail':
                    $type = self::TYPE_THUMBNAIL;
                    break;
                case 'original':
                    $type = self::TYPE_ORIGINAL;
                    break;
                case 'vignette':
                    $type = self::TYPE_VIGNETTE;
                    break;
                case 'icon':
                    $type = self::TYPE_ICON;
                    break;
                default:
					$type = self::TYPE_ORIGINAL;
            }

        }

        $images = new Model_ImageData( array( 'image_id' => $id, 'type' => $type ) );
        $images = $images->search();

        if( count($images) == 0 ){
            $images = new Model_ImageData( array( 'image_id' => $id, 'type' => self::TYPE_ORIGINAL ) );
            $images  = $images->search();

            if( count( $images ) == 0 ){
                die( 'Image not found!');
            }

            $original = $images[0];
            $source = new Nano_Gd( $images[0]->data, false );

            switch( $type ){
                case self::TYPE_ICON:
                    list( $width, $height ) = explode( 'x', self::IMAGESIZE_ICON );
                    break;
                case self::TYPE_THUMBNAIL:
                    list( $width, $height ) = explode( 'x', self::IMAGESIZE_THUMBNAIL );
                    break;
                case self::TYPE_VIGNETTE:
                    list( $width, $height ) = explode( 'x', self::IMAGESIZE_VIGNETTE );
                    list( $x, $y ) = array_values($source->getDimensions());

                    $width = ( $x > $y ) ? $width : ( $height / $y ) * $x;
                    $height = ( $x < $y ) ? $height : ( $width / $x ) * $y;
                    break;
                default:
                    die( 'Image not found!');
            }

            $target = $source->resize( $width, $height );
            $data   = $target->getImageJPEG();

            $image = new Model_ImageData(array(
                    'imageId'   => $id,
                    'size'      => strlen($data),
                    'mime'      => 'image/jpeg',
                    'width'     => $width,
                    'height'    => $height,
                    'data'      => $data,
                    'filename'  => $request->id . '_' . $original->filename,
                    'type'      => $type
            ));

            $image->save();
        }
        else{
            $image = $images[0];
        }

        header( 'Content-Type: ' . $image->mime );
        echo $image->data;
    }

    public function postDispatch(){
        $this->getView()->actions = $this->getMenu();
    }

    protected function getMenu(){
        $menu = array();

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
