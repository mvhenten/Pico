<?php
/**
 * Application/plugin/Navtree/Helper/Navigation.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


/**
 * pathparts = 'foo', 'bar'.
 *
 * navigation( foo, bar, 2 ) should return
 * items that are children of the element bar, who is a child of the
 * element foo. both foo and bar are marked active.
 *
 * navigation( foo, bar, 1 ) should return
 * items taht are children of the element foo. the element bar
 * is marked as active.
 *
 * navigation( foo, bar, biz, 3 ) shold return
 * items that are children of the element biz.
 * navigation( foo, bar, biz 2 ) returns children of the element bar.
 *
 * the element biz has a property named short url. this is the name and identifier
 * of biz, and is similar to the path biz. it has a property url, that is a combination
 * of path parts foo, bar and biz.
 *
 * An element is active if it's identifier matches one of the path parts.
 *
 *
 */
class Navtree_Helper_Navigation extends Nano_View_Helper {

    private $_tree_cache = array();


    /**
     * Returns array of navigation items
     *
     * @param string  $name       name (slug) of the top level parent
     * @param array   $path_parts (optional) Path segments of the request url
     * @param int     $level      (optional) Navigation level, default 0
     * @return array $nav_items
     */
    function Navigation( $name, array $path_parts = array(), $level = null  ) {
        $level      = is_int( $level ) ? $level : count( $path_parts );
        $traverse   = array_slice( $path_parts, 0, $level );
        $children   = $this->get_tree_items( $name );

        while ( count( $traverse ) ) {
            $path_part   = array_shift( $traverse );
            $parent      = $this->find_parent( $children, $path_part );

            if ( $parent === null )
                return;

            $children = $parent->children;
        }

        $children = $this->normalize_children( $children, $path_parts, $level );
        return $children;
    }


    /**
     * Retrieves and caches Navtree_Model_Tree items
     *
     * @param unknown $name
     * @return unknown
     */
    private function get_tree_items( $name ) {
        if ( ! isset( $this->_tree_cache[$name] ) ) {
            $this->_tree_cache[$name] = new Navtree_Model_Tree( $name );
        }

        // NB can be tree as well.
        return $this->_tree_cache[$name]->tree();
    }


    /**
     * Returns a child who's url resembles $pat_part
     *
     * @param array   $children
     * @param string  $path_part
     * @return object $nav_item
     */
    private function find_parent( array $children, $path_part ) {
        foreach ( $children as $child ) {
            $item = $child->item;

            if ( trim( $item->url(), '/') == $path_part ) {
                return $child;
            }
        }
    }


    /**
     * Normalizes the $child elements - builds the target url
     * from $path_parts and sets active child.
     *
     * @param array   $children
     * @param array   $path_parts
     * @return unknown
     */
    private function normalize_children( array $children, array $path_parts, $level=0 ) {
        $collect = array();

        foreach ( $children as $child ) {
            $item           = $child->item;
            $item_path_part = trim( $item->url(), '/' );

            $item_parts = $level == 0 ? array() : array_slice( $path_parts, 0, $level);
            array_push( $item_parts, $item_path_part );

            $collect[] = (object) array(
                'url'       => '/' . join( '/', $item_parts ),
                'name'      => $item->name,
                'short_url' => $item->url(),
                'active'    => $this->is_active_item( $path_parts, $item_parts ),
            );
        }

        return $collect;
    }

    private function is_active_item( $original_path_parts, $item_parts ){
        $item_path = join( '/', $item_parts );
        $original  = substr( join( '/', $original_path_parts ), 0, strlen($item_path) );

        return $item_path == $original;



        //foreach( $item_parts as $index => $path_part ){
        //    if( ! $original_path_parts[$index] ==  )
        //}
        //
        //// item is only active if all item_parts are in original_path_parts
        //foreach( $item_parts as $part ){
        //    if( ! in_array( $part, $original_path_parts ) ){
        //        return false;
        //    }
        //}
        //return true;
    }


}
