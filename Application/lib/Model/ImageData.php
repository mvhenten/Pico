<?php
class Pico_Model_ImageData extends Pico_Schema_ImageData {
    const IMAGESIZE_THUMBNAIL   = '120x120';
    const IMAGESIZE_ICON        = '64x64';
    const IMAGESIZE_SMALL       = '172x144';
    const IMAGESIZE_VIGNETTE    = '400x300';
    const IMAGESIZE_SD          = '640x480';
    const IMAGESIZE_HD          = '640x480';

    private $_image_sizes = array(
        'thumbnail' => self::IMAGESIZE_THUMBNAIL,
        'icon'      => self::IMAGESIZE_ICON,
        'small'     => self::IMAGESIZE_SMALL,
        'vignette'  => self::IMAGESIZE_VIGNETTE,
        'sd'        => self::IMAGESIZE_SD,
        'hd'        => self::IMAGESIZE_HD
    );

    private function _getImageSize( $type ){
        if( isset( $self->_image_sizes[$type] ) ){
            $value = $self->_image_sizes[$type];
            return explode( 'x', $value );
        }
        else{
            $pieces = array_filter(array_map('intval',explode('x', $type)));
            if( count($pieces) == 2 ){
                return $pieces;
            }
            else{
                die( 'INVALID TYPE OR SIZE ' . $type);
            }
        }
    }

    public function resize( $type ){
        $gd = new Nano_Gd( $this->data, false );

        list( $width, $height ) = $this->_getImageSize( $type );
        list( $w, $h ) = array_values( $gd->getDimensions() );

        $ratio = min( $width/$w, $height/$h, 1);

        list( $width, $height ) = array( min( $width, $ratio*$w), min( $height, $ratio*$h) );

        $target = $gd->resize( $width, $height );
        $data   = $target->getImageJPEG();

        preg_match( '/^(\w+)\.(\w+)?/', $original->filename, $match );

        list(,$base, $ext) = $match;

        $new = Pico_Schema_ImageData();

        $new->data     = $target->getImageJPEG();
        $new->size     = strlen($data);
        $new->mime     = 'image/jpeg';
        $new->width    = $width;
        $new->height   = $height;
        $new->filename = sprintf('%s_%s.%s', $base, $typename, $ext);
        $new->image_id = $original->image_id;
        $new->type     = $typename;

        $new->store();

        return $new;
    }
}
