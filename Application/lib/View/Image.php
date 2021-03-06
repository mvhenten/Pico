<?php
/**
 * Application/lib/View/Image.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Nano
 */


class Pico_View_Image extends Nano_App_View{

    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     */
    public function get( Nano_App_Request $request, $config ) {
        list( , $type, $id ) = $request->pathParts();

        date_default_timezone_set('Europe/Amsterdam');

        if ( isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) {
            $since = strtotime( $_SERVER['HTTP_IF_MODIFIED_SINCE'] );

            header( 'Last-Modified: ' . date('r', $since ), true, 304 );
            header( 'Expires: ' . date( 'r', strtotime('+1 Month', $since ) ) );
            header( 'Cache-Control: max-age=36000, must-revalidate' );
            exit;
        }

        $image = $this->_getImageType( $id, $type );
        $this->_imageOut( $image );
        // TODO enable this in future
        // $this->_writeImageFile( $request, $image, $type );
    }

    private function _writeImageFile( $request, $image, $type ) {
        echo( is_writable( is_writable( $request->documentRoot() . '/image' ) ) );
        if( is_writable( $request->documentRoot() . '/image' ) ){
            $path = sprintf( '%s/image/%s', $request->documentRoot(), $type );
            mkdir( $path );
            echo( 'Writing to ' . $path );
            file_put_contents( sprintf( '%s/%s.jpg', $path, $image->image_id ), $image->data );
        }
    }


    /**
     *
     *
     * @param unknown $id
     * @param unknown $type
     * @return unknown
     */
    private function _getImageType( $id, $type ) {
        $imagedata = new Pico_Model_ImageData();

        $image = $imagedata->search( array('where' => array(
                    'image_id'  => $id,
                    'type'      => $type
                )))->fetch();


        if ( false === $image ) {
            return $this->_createImageType( $id, $type );
        }

        return $image;
    }


    /**
     *
     *
     * @param unknown $id
     * @param unknown $type
     * @return unknown
     */
    private function _createImageType( $id, $type ) {
        $imagedata = new Pico_Model_ImageData();

        $images = $imagedata->search( array('where' => array(
                    'image_id'  => $id,
                    'type'      => 'original'
                )));

        $original = $images->fetch();

        if ( $original ) {
            $image = $original->resize( $type );
            return $image;
        }

        throw new Exception( "Image $id cannot be found" );
    }


    /**
     *
     *
     * @param unknown $image
     * @param unknown $cache (optional)
     */
    private function _imageOut( $image, $cache = true ) {
        $inserted = strtotime( $image->created );

        header( 'Content-Type: ' . $image->mime );
        header( 'Content-length: ' .  strlen($image->data) );
        header( 'Content-Disposition: inline; filename=' . $image->filename );
        header( 'Last-Modified: ' . date('r', $inserted ) );
        header( 'Expires: ' . date( 'r', strtotime('+1 Month', $inserted ) ) );
        header( 'Cache-Control: max-age=36000, must-revalidate' );
        header( 'Pragma: cache');
        echo $image->data;
        exit;
    }


}
