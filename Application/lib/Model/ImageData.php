<?php
class Pico_Model_ImageData extends Pico_Schema_ImageData {
    private $_common_types = array(
        'thumbnail'   => '120x120',
        'icon'        => '64x64',
        'small'       => '172x144',
        'vignette'    => '400x300',
        'sd'          => '640x480',
        'hd'          => '960x720'
    );

    public function resize( $type ){
        @list( $width, $height ) = $this->_parseType( $type );

        error_log( 'resizing to ' . $type );

        $im = new Nano_IM_Resize( $this->data, array(
            'width' => $width,
            'height' => $height
        ));

        $basename = basename( $this->filename );
        $name_pieces = explode( '.', $basename );

        preg_match( '/^(.+)\.(\w{3,4})?/', $this->filename, $match );
        list(,$base, $ext) = $match;

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

    private function _parseType( $type ){
        if( isset( $this->_common_types[$type] ) ){
            $type = $this->_common_types[$type];
        }

        $size = array_map( 'intval', explode( 'x', $type ));

        if( count( $size ) == 0 ){
            throw new Exception( "Invalid image type: $type" );
        }

        return $size;
    }
}
