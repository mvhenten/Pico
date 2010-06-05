<?php
class Controller_Image extends Nano_Controller{
    public function preDispatch(){
        $this->getView()->disableViewScript();
        $this->getView()->disableLayout();
    }

    public function viewAction(){
        $request = $this->getRequest();

        $type = $request->type;
        $id   = $request->id;

        if( is_numeric($request->type) && null == $request->id ){
            $type = 'original';
            $id   = $request->type;
        }

        $image = new Model_Image(array('id'=>$id));
        $this->_imageOut( $image, $type );
    }

    private function _imageOut( $image, $type, $cache = true ){
        date_default_timezone_set('Europe/Amsterdam');

        $inserted = strtotime($image->inserted);

        if( $cache && isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ){
            $since = strtotime( $_SERVER['HTTP_IF_MODIFIED_SINCE'] );

            if( $since === $inserted ){
                header( 'Last-Modified: ' . date('r', $inserted ), true,304 );
                header( 'Expires: ' . date( 'r', strtotime('+1 Month', $inserted ) ) );
                header( 'Cache-Control: max-age=36000, must-revalidate' );
                exit;
            }
        }

        $data = $image->fetchData( $type );

        header( 'Content-Type: ' . $data->mime );
        header( 'Content-length' .  $data->size );
        header( 'Content-Disposition: inline; filename=' . $data->filename );
        header( 'Last-Modified: ' . date('r', $inserted ) );
        header( 'Expires: ' . date( 'r', strtotime('+1 Month', $inserted ) ) );
        header( 'Cache-Control: max-age=36000, must-revalidate' );
        header( 'Pragma: cache');
        echo $data->data;
        exit;
    }

}
