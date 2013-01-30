<?php
/**
 * Application/plugin/Video/Helper/Video.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


class Video_Helper_Video extends Nano_View_Helper {


    /**
     *
     *
     * @param unknown $item
     * @return unknown
     */
    function Video( $item ) {
        $appendix = $item->appendix;

        if ( ! isset( $appendix->video ) ) {
            return null;
        }

        if ( ! isset( $appendix->video->url ) ) {
            return null;
        }

        return $this->_getVimeoEmbed( $item );
    }



    /**
     *
     *
     * @param unknown $item
     * @return unknown
     */
    private function _getVimeoEmbed( $item ) {
        $video = (array) $item->appendix->video;

        preg_match(  '/.+?(\d+)/', $video['url'], $matches );

        $template = '<iframe src="http://player.vimeo.com/video/%d" width="%d" height="%d"
            frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
            <p><a href="%s">%s</a>';

        return sprintf( $template,
            htmlentities( $matches[1] ),
            htmlentities( $video['width'] ),
            htmlentities( $video['height'] ),
            htmlentities( $video['url'] ),
            htmlentities( $item->name )
        );
    }


}
