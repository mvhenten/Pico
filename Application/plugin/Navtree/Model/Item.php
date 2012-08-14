<?php
/**
 * Application/plugin/Navtree/Model/Item.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


// @todo model_item is too much
class Navtree_Model_Item extends Pico_Model_Item {


    /**
     *
     *
     * @return unknown
     */
    public function url() {
        return isset($this->appendix->url) ? $this->appendix->url : '';
    }


    /**
     *
     *
     * @param unknown $path
     * @return unknown
     */
    public function isActive( $path ) {
        $path = trim( $path, '/' );
        $url  = trim( $this->url(), '/' );

        return $url === $path;
    }


    /**
     * Return all items that are children of this item
     *
     * @return PdoStatement $items
     */
    public function items() {
        $sth = $this->has_many( 'Navtree_Model_Item', array(
                'type' => 'type'
            ), array(
                'order' => array( 'parent', 'priority' )
            ));

        return $sth->fetchAll();

    }


}
