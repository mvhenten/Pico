<?php
class Controller_Admin_Image extends Pico_AdminController{
    const IMAGESIZE_THUMBNAIL = '64x64';
    const IMAGESIZE_ICON      = '32x32';
    const IMAGESIZE_VIGNETTE   = '400x300';

    const TYPE_ORIGINAL       = 1;
    const TYPE_VIGNETTE       = 2;
    const TYPE_THUMBNAIL      = 3;
    const TYPE_ICON           = 4;
    const TYPE_CUSTOM         = 5;

    public function indexAction(){
        $this->getView()->content = "Hello World";
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

        $form->addElements(array(
            'image' => array(
                'type'  => 'file'
            ),
            'upload'    => array(
                'type'  => 'submit',
                'value' => 'Submit'
            )
        ));

        $this->getView()->form = $form;
    }

    public function editAction(){
        $request = $this->getRequest();
        $this->getView()->image = new Model_Image( array('id'=>$request->id) );
    }

    protected function getItemForm( $item ){

    }

    public function viewAction(){
        $request = $this->getRequest();

        $id = $request->id;
        $type = 1;

        if( !is_numeric( $request->id ) && is_numeric( $request->value ) ){
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
                    die('Invalid type: ' . $type );
            }

            $id = $request->value;
        }
        else{
            die('Invalid paramters given');
        }


        $images = new Model_ImageData( array( 'imageId' => $id, 'type' => $type ) );
        $images = $images->search();

        if( count($images) == 0 ){
            $images = new Model_ImageData( array( 'imageId' => $id, 'type' => self::TYPE_ORIGINAL ) );
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

        //echo $imageData->type;

        exit;


        $project = new Model_Project(array('id'=>$request->id));

        //@todo fix this wiht a real user
        $project->userId = 1;

        if( null == $project->name ){
            $this->_pageNotFound( "Invalid project id" );
        }

        $image = new Model_Image(array('projectId' => $project->id ));

        $this->getView()->images    = $image->search();
        $this->getView()->projects  = $project->search();
        $this->getView()->project   = $project;
    }

    public function postDispatch(){
        $this->getView()->actions = $this->getMenu();
    }

    protected function getMenu(){
        return array(
            'add'   => array(
                'target' => '/admin/image/add',
                'value'  => 'Add new image'
            )
        );
    }
}
