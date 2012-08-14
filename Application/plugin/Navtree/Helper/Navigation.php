<?php
/**
 * Application/plugin/Navtree/Helper/Navigation.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


class Navtree_Helper_Navigation extends Nano_View_Helper {

    private $_trees;
    private $_url_item_cache = array();


    /**
     *
     *
     * @param unknown $name
     * @param unknown $parent (optional)
     * @return unknown
     */
    function Navigation( $name, $parent=null  ) {
        if ( $parent ) {
            $parent_item = $this->_find_parent( $name, $parent );

            if ( null === $parent_item )
                return null;

            $tree = $parent_item->children;
        }
        else {
            $tree = $this->_model_tree( $name )->tree();
        }

        $items = $this->_plain_items( $tree );

        return ( count($items) > 0 ) ? $items : null;
    }



    /**
     *
     *
     * @param unknown $items
     * @return unknown
     */
    private function _plain_items( $items ) {
        $plain_items = array();

        foreach ( $items as $item ) {
            $plain_items[] = $item->item;
        }

        return $plain_items;

    }


    /**
     *
     *
     * @param unknown $name
     * @param unknown $path
     * @return unknown
     */
    private function _find_parent( $name, $path ) {
        $items = $this->_url_item_cache( $name );
        $path  = trim( $path, '/' );

        return isset($items[$path]) ? $items[$path] : null;
    }


    /**
     *
     *
     * @param unknown $name
     * @return unknown
     */
    private function _url_item_cache( $name ) {
        if ( !isset( $this->_url_item_cache[$name] ) ) {
            $cache = array();

            $model = $this->_model_tree( $name );

            foreach ( $model->items() as $item ) {
                $cache[trim($item->item->url(), '/')] = $item;
            }

            $this->_url_item_cache[$name] = $cache;
        }

        return $this->_url_item_cache[$name];
    }



    /**
     *
     *
     * @param unknown $name
     * @return unknown
     */
    private function _model_tree( $name ) {
        if ( ! isset( $this->_trees[$name] ) ) {
            $this->_trees[$name] = new Navtree_Model_Tree( $name );
        }
        return $this->_trees[$name];
    }


}
