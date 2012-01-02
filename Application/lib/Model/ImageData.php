<?php
class Pico_Model_ImageData extends Pico_Schema_ImageData {
    const IMAGESIZE_THUMBNAIL   = '120x120';
    const IMAGESIZE_ICON        = '64x64';
    const IMAGESIZE_SMALL       = '172x144';
    const IMAGESIZE_VIGNETTE    = '400x300';
    const IMAGESIZE_SD          = '640x480';
    const IMAGESIZE_HD          = '640x480';

    public function resize( $type ){
        $gd = new Nano_Gd( $this->data, false );

        list( $width, $height ) = $this->_getProportionalResize($gd, $type);

        $target = $gd->resize( $width, $height );
        $data   = $target->getImageJPEG();
        $basename = basename( $this->filename );
        $name_pieces = explode( '.', $basename );

        preg_match( '/^(.+)\.(\w{3,4})?/', $this->filename, $match );
        list(,$base, $ext) = $match;

        $new = new Pico_Schema_ImageData(array(
            'data'    => $target->getImageJPEG(),
            'size'    => strlen($data),
            'mime'    => 'image/jpeg',
            'width'   => $width,
            'height'  => $height,
            'filename'=> sprintf('%s_%s.%s', $base, $type, $ext),
            'image_id'=> $this->image_id,
            'type'    => $type
        ));

        return $new->store();
    }

    private function _getProportionalResize( $gd, $type ){
        list( $width, $height ) = $this->_getImageSize( $type );
        list( $w, $h ) = array_values( $gd->getDimensions() );

        if( $width > $height ){
            $ratio = $width/$w;
        }
        else{
            $ratio = $height/$h;
        }

        $dimensions = array( (int) ($ratio*$w) , (int) ($ratio*$h) );
        return $dimensions;
    }

    private function _getImageSize( $type ){
        $_defaults = $this->_getImageSizeDefaults();

        if( isset( $_defaults[$type] ) ){
            $size = isset( $_defaults[$type] ) ? $_defaults[$type] : $_defaults['thumbnail'];
            list( $width, $height ) = explode('x', $size );
        }
        else{
            /**
             * allow resizing arbitrary sizes.
             * @todo remove this in favor of a settings dialog!
             */
            if( intval($type) ){
                $width = intval($type);
                $height = 1;
            }
            else if( ($dims = explode('x',$type)) && count($dims) == 2 ){
                @list( $width, $height ) = $dims;
                $width = max($width, 1);
                $height = max($height,1);
            }
            else{
                throw new Exception('Cannot resize image to ' . $type );
            }
        }

        return array( $width, $height );
    }

    private function _getImageSizeDefaults(){
        return array(
            'thumbnail' => Nano_Gd::IMAGESIZE_THUMBNAIL,
            'icon'      => Nano_Gd::IMAGESIZE_ICON,
            'small'     => Nano_Gd::IMAGESIZE_SMALL,
            'vignette'  => Nano_Gd::IMAGESIZE_VIGNETTE,
            'sd'        => Nano_Gd::IMAGESIZE_SD,
            'hd'        => Nano_Gd::IMAGESIZE_HD
        );
    }
}
