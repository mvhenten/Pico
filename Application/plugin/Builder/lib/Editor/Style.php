<?php
class Builder_Editor_Style extends Builder_Abc {
    protected $_margin;
    protected $_padding;
    protected $_width;
    protected $_height;

    protected function _build__margin () {
        $styles = $this->arg('margin');
        $css = '';

        foreach( $styles as $pos => $value ){
            $css .= "$pos: -$value;";
        }

        return $css;
    }

    protected function _build__padding () {
        $styles = $this->arg('padding');
        $css = '';

        foreach( $styles as $pos => $value ){
            $css .= "$pos: $value;";
        }

        error_log( 'padding: ' . $css );

        return $css;
    }

    protected function _build__height(){
        $width = $this->arg( 'height' );

        if( !$width ){
            return '';
        }

        return "height: $width;";

    }

    protected function _build__width(){
        $width = $this->arg( 'width' );

        if( !$width ){
            $width = '2em';
        }

        return "width: $width;";
    }




}
