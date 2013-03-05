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

        $url = new Nano_Url( $appendix->video->url );

        if ( $url->host == 'vimeo.com' ) {
            return $this->_getVimeoEmbed( $item );
        }

        if ( $url->host == 'www.youtube.com' ) {
            return $this->_getYoutubeEmbed( $item );
        }
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



    /**
     *
     *
     * @param unknown $item
     * @return unknown
     */
    private function _getYoutubeEmbed( $item ) {
        $video = (array) $item->appendix->video;

        preg_match(  '/.+?v=(\w+)/', $video['url'], $matches );

        $template = '<iframe src="http://www.youtube.com/embed/%s?feature=player_detailpage"
            width="%d" height="%d" frameborder="0" allowfullscreen></iframe>';

        return sprintf( $template,
            htmlentities( $matches[1] ),
            htmlentities( $video['width'] ),
            htmlentities( $video['height'] )
        );
    }


}
