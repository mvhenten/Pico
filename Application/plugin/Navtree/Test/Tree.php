<?php
/**
 * Application/plugin/Navtree/Test/Tree.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


class Navtree_Test_Tree {

    /**
     *
     *
     * @param unknown $tree_name
     * @return unknown
     */
    public static function create( $tree_name  ) {
        $parent = self::_create_item( $tree_name );

        self::_create_children( $parent );
        return $parent;
    }


    /**
     *
     *
     * @param unknown $parent
     */
    public static function _create_children( $parent  ) {
        foreach ( range(0, 3) as $priority ) {
            $nav = self::_create_item( "nav_parent_$priority", $parent->id, $priority );

            foreach ( range(0, 3) as $priority_child ) {
                $child = self::_create_item( "nav_{$nav->id}_child_$priority", $nav->id, $priority_child );

                foreach ( range(3, 0) as $priority_sub ) {
                    $child_sub = self::_create_item( "child_{$child->id}_sub_$priority_sub", $child->id, $priority_sub );
                }
            }
        }
    }


    /**
     *
     *
     * @param unknown $slug
     * @param unknown $parent   (optional)
     * @param unknown $priority (optional)
     * @return unknown
     */
    private static function _create_item( $slug, $parent=0, $priority=0 ) {
        $item = new Pico_Model_Item(array(
                'parent'    => $parent,
                'slug'      => $slug,
                'type'      => 'navigation',
                'priority' => $priority  ,
            ));

        $item->appendix = (object) array( 'url' => "/$slug" );
        $item->store();

        return $item;

    }


}
