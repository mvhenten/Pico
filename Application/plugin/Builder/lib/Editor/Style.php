<?php
/**
 * Application/plugin/Builder/lib/Editor/Style.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


class Builder_Editor_Style extends Builder_Abc {
    protected $_display_margin;
    protected $_padding;
    protected $_width;
    protected $_height;

    /**
     *
     *
     * @return unknown
     */
    protected function _build__display_margin() {
        $styles = $this->arg('margin');
        $css = '';

        foreach ( $styles as $pos => $value ) {
            $css .= "$pos: -$value;";
        }

        return $css;
    }


    /**
     *
     *
     * @return unknown
     */
    protected function _build__padding() {
        $styles = $this->arg('padding');
        $css = '';

        foreach ( $styles as $pos => $value ) {
            $css .= "$pos: $value;";
        }

        error_log( 'padding: ' . $css );

        return $css;
    }


    /**
     *
     *
     * @return unknown
     */
    protected function _build__height() {
        $width = $this->arg( 'height' );

        if ( !$width ) {
            return '';
        }

        return "height: $width;";

    }


    /**
     *
     *
     * @return unknown
     */
    protected function _build__width() {
        $width = $this->arg( 'width' );

        if ( !$width ) {
            $width = '2em';
        }

        return "width: $width;";
    }




}
