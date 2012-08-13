<?php
/**
 * Application/plugin/Navtree/Model/Navtree.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


class Navtree_Model_Tree {

    private $_navTree;
    private $_navTreeStack;
    private $_item;
    private $_items;
    private $_name;



    /**
     *
     *
     * @param unknown $name
     */
    public function __construct( $name ) {
        $this->_name = $name;
    }



    /**
     *
     *
     * @param unknown $item
     * @return unknown
     */
    public function children( $item ) {
        $this->tree(); // initialize build

        return $this->_navTreeStack[$item->id];
    }


    /**
     *
     *
     * @return unknown
     */
    public function tree() {
        if ( $this->_navTree === null ) {
            $this->_navTree = $this->_build_navTree( $this->_item()->items() );
        }

        return $this->_navTree;
    }

    /**
     *
     *
     * @return unknown
     */
    public function items() {
        $this->tree();
        
        return $this->_navTreeStack;
    }



    /**
     *
     *
     * @return unknown
     */
    private function _item() {
        if ( null === $this->_item ) {
            $model = new Navtree_Model_Item();
            $this->_item = $model->search(array('slug' => $this->_name, 'type' => 'navigation' ))->fetch();
        }


        return $this->_item;
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
            if ( $value->item->parent == $this->_item()->id ) {
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

        $this->_navTreeStack = $stack;

        return $stack;
    }


}
