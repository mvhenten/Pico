<?php
/**
 * Application/plugin/ItemThumbnail/Helper/Thumbnail.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Bison
 */


class ItemThumb_Helper_Thumbnail extends Nano_View_Helper {


    /**
     *
     *
     * @param unknown $item
     * @param unknown $size    (optional)
     * @param unknown $wrapper (optional)
     * @return unknown
     */
    function Thumbnail( $item, $size='original', $wrapper = array() ) {
        $wrapper = array_merge(array(
                'tag' => 'li',
                'class' => 'thumbnail'
            ), $wrapper );

        if ( isset($item->appendix->thumbnail) ) {
            return sprintf('<%s title="%s" class="%s"><a href="/%s">
                           <img alt="%s" src="/image/%s/%d?v=%d" /></a></%s>',
                $wrapper['tag'],
                $item->name,
                $wrapper['class'],
                $item->slug,
                $item->name,
                $size,
                $item->id,
                $item->appendix->thumbnail,
                $wrapper['tag']
            );
        }
    }


}
