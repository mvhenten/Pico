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
     * @param unknown $n_children (optional)
     * @return unknown
     */
    public static function create( $tree_name, $n_children = 3 ) {
        $parent = self::_create_item( $tree_name );

        self::_create_children( $parent, $n_children );
        return $parent;
    }


    /**
     *
     *
     * @param unknown $parent
     * @param unknown $n_children (optional)
     */
    public static function _create_children( $parent, $n_children = 3  ) {
        $max = $n_children - 1;

        foreach ( range(0, $max) as $priority ) {
            $nav = self::_create_item( "nav_parent_$priority", $parent->id, $priority );
            // print( "Created item: $nav->name\n" );

            foreach ( range(0, $max) as $priority_child ) {
                $child = self::_create_item( "nav_{$nav->id}_child_$priority", $nav->id, $priority_child );

                // print( "    Created item: $child->id, $child->name\n" );

                foreach ( range($max, 0) as $priority_sub ) {
                    $child_sub = self::_create_item( "child_{$child->id}_sub_$priority_sub", $child->id, $priority_sub );
                    // print( "        Created item: $child_sub->name\n" );
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
                'name'     => "Name of $slug",
            ));

        $item->appendix = (object) array( 'url' => "/$slug" );
        $item->store();

        return $item;

    }


}
