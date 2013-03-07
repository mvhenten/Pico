<?php
/**
 * Application/lib/Model/ImageData.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


class Pico_Model_ImageData extends Pico_Schema_ImageData {
    private $_common_types = array(
        'thumbnail'   => '120x120',
        'icon'        => '64x64',
        'small'       => '172x144',
        'vignette'    => '400x300',
        'sd'          => '640x480',
        'hd'          => '960x720'
    );



    /**
     *
     *
     * @param unknown $path
     * @param unknown $name
     * @param unknown $item
     * @return unknown
     */
    public static function createFromPath( $path, $name, $item ) {
        $original   = file_get_contents( $path );
        $info       = getimagesize( $path );

        @list( $width, $height ) = $info;

        //        $gd   = new Nano_Gd( $path );
        // $exif = new Nano_Exif( $path );
        // $gd   = self::_rotateImageData( $exif, $gd );

        $width  = $width > 1024 ? 1024 : $width;
        $height = $height > 1024 ? 1024 : $height;

        $im = new Nano_IM_Resize( file_get_contents($path), array(
                'width' => $width,
                'height' => $height
            ));

        $data = $im->asString();

        error_log(sprintf("Got imagesize: %0.2f $width x $height ", ( strlen($data) / (1024*1024))));

        $image_data = new Pico_Model_ImageData(array(
                'image_id'  => $item->id,
                'size'      => strlen($data),
                'mime'      => 'image/jpeg',
                'width'     => $width,
                'height'    => $height,
                'data'      => $data,
                'filename'  => $name,
                'type'      => 'original'
            ));

        $image_data->store();

        return $image_data;
    }



    /**
     *
     *
     * @param object  $exif
     * @param object  $gd
     * @return unknown
     */
    public static function _rotateImageData( Nano_Exif $exif, Nano_Gd $gd ) {
        switch ( $exif->orientation() ) {
        case 2:
            return $gd->flipHorizontal();
        case 3:
            return $gd->rotate( 180 );
        case 4:
            return $gd->flipVertical();
        case 5:
            return $gd->flipVertical()->rotate(90);
        case 6:
            return $gd->rotate( -90 );
        case 7:
            return $gd->flipHorizontal()->rotate( -90 );
        case 8:
            return $gd->rotate( 90 );
        }

        return $gd;
    }


    /**
     *
     *
     * @param unknown $type
     * @return unknown
     */
    public function resize( $type ) {
        @list( $width, $height ) = $this->_parseType( $type );

        // error_log( 'resizing to ' . $type );

        $im = new Nano_IM_Resize( $this->data, array(
                'width' => $width,
                'height' => $height
            ));

        $basename = basename( $this->filename );
        $name_pieces = explode( '.', $basename );

        preg_match( '/^(.+)\.(\w{3,4})?/', $this->filename, $match );
        list(, $base, $ext) = $match;

        $data = (string) $im;

        $new = new Pico_Model_ImageData(array(
                'data'    => $data,
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


    /**
     *
     *
     * @param unknown $type
     * @return unknown
     */
    private function _parseType( $type ) {
        if ( isset( $this->_common_types[$type] ) ) {
            $type = $this->_common_types[$type];
        }

        $size = array_map( 'intval', explode( 'x', $type ));

        if ( count( $size ) == 0 ) {
            throw new Exception( "Invalid image type: $type" );
        }

        return $size;
    }


}
