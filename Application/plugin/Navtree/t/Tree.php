<?php
/**
 * Application/plugin/Tree/t/Model.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


class Pico_Test_testNavtree extends PHPUnit_Framework_TestCase{

    /**
     * load pico/nano library
     */
    public static function setUpBeforeClass() {
        require_once '../../lib/Test/Autoload.php';
        $plugin_base = dirname(dirname(__FILE__));

        Pico_Test_Autoload::register();
        Pico_Test_Db::load_pico_schema();

        $schema = new Pico_Schema_Item();

        Nano_Autoloader::registerNamespace( 'Navtree', $plugin_base  );
        $dbh = Nano_Db::getAdapter();

        $top_parent = self::_setupCreateNavitem( 0, 0 );

        foreach ( range(0, 3) as $priority ) {
            $parent = self::_setupCreateNavitem( $top_parent, $priority+1 );

            foreach ( range(0, 3) as $priority_child ) {
                $child = self::_setupCreateNavitem( $parent, $priority_child+1 );

                foreach ( range(3, 0) as $priority_sub ) {
                    $sub = self::_setupCreateNavitem( $child, $priority_sub+1 );
                }
            }
        }
    }



    /**
     *
     *
     * @param unknown $parent
     * @param unknown $priority
     * @return unknown
     */
    public static function _setupCreateNavitem( $parent, $priority ) {
        $slug = $parent == 0 ? 'nav_parent' : join('_', array('child_of_', $parent, $priority ) );
        
        $item = new Pico_Model_Item(array(
                'parent' => $parent,
                'slug' => $slug,
                'type' => 'navigation',
                'priority' => $priority  ,
            ));

        $item->store();
        return $item->id;
    }


    /**
     *
     */
    public function test_tree_order() {
        $tree = new  Navtree_Model_Tree('nav-parent');

        $items = $tree->tree();
        $stack = $items;

        $this->_navTree_ok( $stack );
    }



    /**
     *
     */
    public  function test_tree_children() {
        $tree = new  Navtree_Model_Tree('nav-parent');

        $items = $tree->tree();

        foreach ( $items as $item ) {
            $children = $tree->children( $item->item );
            $this->_navTree_ok( array( $children ) );
        }

    }



    /**
     *
     *
     * @param array   $stack
     */
    private function _navTree_ok( array $stack ) {
        @list( $last_prio, $last_parent ) = array( 0, 0 );

        while ( $stack ) {
            $item = array_shift( $stack );
            $schema_item = $item->item;

            if ( count($item->children) ) {
                array_splice( $stack, count($stack), 0, $item->children );
            }

            if ( $schema_item->parent !== $last_parent ) {
                $last_parent = $schema_item->parent;
                $last_prio   = 0;
            }

            $this->assertTrue( $last_prio < $schema_item->priority );
            $this->assertInstanceOf( 'Navtree_Model_Item', $schema_item );

            $last_prio = $schema_item->priority;
        }
    }


}
