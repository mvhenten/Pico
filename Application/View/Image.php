<?php
class View_Image extends Nano_View{
    protected function get( $request, $config ){
        $imagedata = new Pico_Model_ImageData();

        $images = $imagedata->search( array('where' => array(
            'image_id'  => $request->id,
            'type'      => $request->type
        )));

        if( $images->rowCount() == 0 ){
            $images = $imagedata->search( array('where' => array(
                'image_id'  => $request->id,
                'type'      => 'original'
            )));

            $original = $images->fetch();

            if( $original ){
                $image = $original->resize( $request->type );
                $this->_imageOut( $image );
            }
        }
        else{
            $image = $images->fetch();
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

        header( 'Content-Type: ' . $image->mime );
        header( 'Content-length: ' .  $image->size );
        header( 'Content-Disposition: inline; filename=' . $image->filename );
        header( 'Last-Modified: ' . date('r', $inserted ) );
        header( 'Expires: ' . date( 'r', strtotime('+1 Month', $inserted ) ) );
        header( 'Cache-Control: max-age=36000, must-revalidate' );
        header( 'Pragma: cache');
        echo $image->data;
        exit;
    }

}
