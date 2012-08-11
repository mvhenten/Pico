<?php
/**
 * Application/plugin/Navtree/Model/Navtree.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


class Navtree_Model_Navtree extends Pico_Schema_Item {

    private $_navTree;

    /**
     *
     *
     * @return unknown
     */
    public function tree() {
        if ( $this->_navTree === null ) {
            $this->_navTree = $this->_build_navTree( $this->_navigationItems() );
        }

        return $this->_navTree;
    }


    /**
     *
     *
     * @return unknown
     */
    function _navigationItems() {
        return $this->has_many( 'Pico_Schema_Item', array(
                'type' => 'type'
            ), array(
                'order' => array( 'parent', 'priority' )
            ));

    }





    /**
     *
     *
     * @param unknown $items
     * @return unknown
     */
    function _build_navTree( $items ) {
        $stack = $this->_build_navTreeStack( $items );

        foreach ( $stack as $child ) {
            $item = $child->item;

            if ( isset( $stack[$item->parent] ) ) {
                array_push( $stack[$item->parent]->children, $child );
            }
        }

        return $this->_build_topLevel( $stack );
    }





    /**
     *
     *
     * @param array   $stack
     * @return unknown
     */
    function _build_topLevel( array $stack ) {
        $top_level = array();

        foreach ( $stack as $value ) {
            if ( $value->item->parent == $this->id ) {
                array_push( $top_level, $value );
            }
        }

        return $top_level;
    }





    /**
     *
     *
     * @param unknown $items
     * @return unknown
     */
    function _build_navTreeStack( $items ) {
        $stack = array();

        foreach ( $items as $child ) {
            // use object, so references to children array
            // are kept when pushing in the next loop
            $stack[$child->id] = (object) array(
                'item' => $child,
                'children' => array()
            );
        }

        return $stack;
    }


}
