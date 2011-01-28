<?php
class Controller_Image extends Nano_Controller{
    //public static $sizes = array(
    //    array(),
    //    array(),
    //    array()
    //);
    //
    //public function preDispatch(){
    //    //$this->getView()->disableViewScript();
    //    //$this->getView()->disableLayout();
    //}
    //
    protected function get( $request, $config ){

        switch( $request->type ){
            case 'vignette':
                $type = Model_ImageData::TYPE_VIGNETTE;
                break;
            case 'thumbnail':
                $type = Model_ImageData::TYPE_THUMBNAIL;
                break;
            case 'icon':
                $type = Model_ImageData::TYPE_ICON;
                break;
            default:
                $type = Model_ImageData::TYPE_ORIGINAL;
        }

        $image  = Model_ImageData::get()->all()
                ->where( 'image_id', $request->id )
                ->where( 'type', $type )
                ->current();

        if( null == $image ){
            $image = Model_ImageData::get()->all()
                ->where('image_id', $request->id )
                ->current();

            $id = Model_ImageData::resize( $image, $type );
            $image = Model_ImageData::get( $id );
        }

        $this->_imageOut( $image );
    }


    private function _imageOut( $image, $cache = true ){
        date_default_timezone_set('Europe/Amsterdam');

        $inserted = strtotime($image->created);

        if( $cache && isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ){
            $since = strtotime( $_SERVER['HTTP_IF_MODIFIED_SINCE'] );

            if( $since === $inserted ){
                header( 'Last-Modified: ' . date('r', $inserted ), true,304 );
                header( 'Expires: ' . date( 'r', strtotime('+1 Month', $inserted ) ) );
                header( 'Cache-Control: max-age=36000, must-revalidate' );
                exit;
            }
        }

        //$data = $image->data;

        header( 'Content-Type: ' . $image->mime );
        header( 'Content-length' .  $image->size );
        header( 'Content-Disposition: inline; filename=' . $image->filename );
        header( 'Last-Modified: ' . date('r', $inserted ) );
        header( 'Expires: ' . date( 'r', strtotime('+1 Month', $inserted ) ) );
        header( 'Cache-Control: max-age=36000, must-revalidate' );
        header( 'Pragma: cache');
        echo $image->data;
        exit;
    }

}
