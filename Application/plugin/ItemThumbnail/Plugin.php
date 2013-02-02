<?php
/**
 * Application/plugin/ItemThumbnail/Plugin.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package default
 */


class ItemThumb_Plugin extends Pico_View_Admin_Base {
    private $_thumbnailDimensions = array(179, 144);


    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     */
    public function get( Nano_App_Request $request, $config ) {
        @list( , , , $action, $item_id ) = $request->pathParts;


        $item = $this->model( 'Item', $item_id );
        unset( $item->appendix->thumbnail );

        $item->store(array('id' => $item_id ));

        if ( $action == 'delete' ) {
            $this->_clearImageData( $item );
        }

        $url  = $this->_itemUrl( $request, $item );
        $this->response()->redirect($url);
    }


    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     */
    public function post( Nano_App_Request $request, $config ) {
        @list( , , , $action, $item_id ) = $request->pathParts;

        $item = $this->model( 'Item', $item_id );
        $file = (object) $_FILES['image'];
        $url  = $this->_itemUrl( $request, $item );

        if ( $file->error ) {
            $url->query_form( array('error' => $file->error ));
            $this->response()->redirect($url);
        }
        else {
            $this->_clearImageData( $item );
        }

        if ( null !== ($info = Nano_Gd::getInfo( $file->tmp_name ))) {
            @list( $image_data, $info ) = $this->_storeImageData( $item, $file, $config );
        }

        @list( $width, $height, $filename ) = $info;

        $item->appendix()->thumbnail      =  $image_data->id;
        $item->appendix()->thumbnail_data = array(
            'width'     => $width,
            'height'    => $height,
            'filename'  => $filename,
            'item_id'   => $image_data->id
        );

        $item->store(array( 'id' => $item_id ) );
        $this->response()->redirect($url);
    }


    /**
     *
     *
     * @param unknown $request
     * @param unknown $item
     * @return unknown
     */
    private function _itemUrl( $request, $item ) {
        $url = $request->url;
        $url->pathParts( array('admin', $item->type, 'edit', $item->id ) );
        return $url;
    }


    /**
     *
     *
     * @param unknown $item
     */
    private function _clearImageData( $item ) {
        $this->model('ImageData')->delete( array('image_id' => $item->id ));
    }


    /**
     *
     *
     * @param unknown $item
     * @param unknown $file
     * @param unknown $config
     * @return unknown
     */
    private function _storeImageData( $item, $file, $config ) {

        $gd  = new Nano_Gd( $file->tmp_name );

        $size = $gd->dimensions;
        list( $width, $height ) = $this->_getSuitableDimensions( $size, $config );

        //if ( $size['width'] < $size['height'] ) {
        //    $gd  = $gd->resize( $width, null );
        //}
        //else {
        //    $gd  = $gd->resize( null, $height );
        //}

        $gd = $gd->crop( $width, $height );
        $data  = $gd->getImageJPEG(100);

        $im = new Nano_IM_Resize( $data, array(
                'width' => $width,
                'height' => $height
            ));

        $data = (string) $im;


        $image_data = $this->model('ImageData', array(
                'image_id'  => $item->id,
                'size'      => strlen($data),
                'mime'      => 'image/jpeg',
                'width'     => $width,
                'height'    => $height,
                'data'      => $data,
                'filename'  => $file->name,
                'type'      => 'original'
            ));

        $image_data->store();

        return array( $image_data, array( $width, $height, $file->name ) );
    }



    /**
     *
     *
     * @param unknown $size
     * @param unknown $config
     * @return unknown
     */
    private function _getSuitableDimensions( $size, $config ) {
        $possible_dimensions = $this->_getThumbnailDimensions( $config );

        foreach ( $possible_dimensions as $dimension ) {
            @list( $width, $height ) = $dimension;

            if ( $width < $height && $size['width'] < $size['height'] ) {
                return $dimension;
            }
            else if ( $width > $height && $size['width'] > $size['height'] ) {
                    return $dimension;
                }
        }

        return $possible_dimensions[0];
    }



    /**
     *
     *
     * @param unknown $config
     * @return unknown
     */
    private function _getThumbnailDimensions( $config ) {
        $config = key_exists( 'thumbnail', $config['config'] )
            ? $config['config']['thumbnail'] : null;

        if ( null != $config && key_exists( 'dimensions', $config ) ) {
            $dimensions;

            foreach ( $config['dimensions'] as $value ) {
                $dim = explode('x', $value );
                if ( count( $dim ) < 2 ) continue;
                $dimensions[] = $dim;
            }

            return $dimensions;
        }

        return array( $this->_thumbnailDimensions );
    }


}
