<?php
/**
 * Application/plugin/News/Helper/News.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


class News_Helper_News extends Nano_View_Helper {


    /**
     *
     *
     * @param unknown $item
     * @return unknown
     */
    function News( $item ) {
        
        $search = new Pico_Model_Item();


        $items = $search->search(array(
                'where' => array('type' =>  'news-item', 'parent' => $item->id ),
                'order' => 'priority'
            ));
        
        return $items->fetchAll();
    }

}
