<?php
class ItemThumb_Helper_Thumbnail extends Nano_View_Helper {
    function Thumbnail( $item, $wrapper = array() ) {
        $wrapper = array_merge(array(
            'tag' => 'li',
            'class' => 'thumbnail'
        ), $wrapper );

        if ( isset($item->appendix->thumbnail) ){
            return sprintf('<%s class="%s"><a href="/%s">
                           <img alt="%s" src="/image/original/%d?v=%d" /></a></%s>',
                $wrapper['tag'],
                $wrapper['class'],
                $item->slug,
                $item->name, $item->id,
                $item->appendix->thumbnail,
                $wrapper['tag']
            );
        }
    }
}
