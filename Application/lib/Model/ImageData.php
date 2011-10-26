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

        list( $width, $height ) = $this->_getImageSize( $type );
        list( $w, $h ) = array_values( $gd->getDimensions() );

        $ratio = min( $width/$w, $height/$h, 1);

        list( $width, $height ) = array( min( $width, $ratio*$w), min( $height, $ratio*$h) );

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

    private function _getImageSize( $type ){
        $_defaults = array(
            'thumbnail' => Nano_Gd::IMAGESIZE_THUMBNAIL,
            'icon'      => Nano_Gd::IMAGESIZE_ICON,
            'small'     => Nano_Gd::IMAGESIZE_SMALL,
            'vignette'  => Nano_Gd::IMAGESIZE_VIGNETTE,
            'sd'        => Nano_Gd::IMAGESIZE_SD,
            'hd'        => Nano_Gd::IMAGESIZE_HD
        );

        if( preg_match( '/\d+x\d+/', $type, $match) ){
            list( $__, $width, $height ) = $match;
        }
        else{
            $size = isset( $_defaults[$type] ) ? $_defaults[$type] : $_defaults['thumbnail'];
            list( $width, $height ) = explode('x', $size );
        }

        return array( $width, $height );
    }
}
