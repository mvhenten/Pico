<?php
/**
 * Application/plugin/Tree/t/Model.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


require_once basename(__FILE__) . '/../include.php';


class Pico_Test_testNavtree extends PHPUnit_Framework_TestCase{

    // 4 is chosen because 4x4x4 is less then 100
    // sqlite sorts alphabetic!
    const n_children = 4;

    /**
     *
     */
    static function setUpBeforeClass() {
        Navtree_Test_Tree::create('nav-parent', self::n_children );
    }


    /**
     *
     */
    public function test_tree_order() {
        Navtree_Test_Tree::create('nav-parent-a' );
        $tree = new  Navtree_Model_Tree('nav-parent-a');

        $items = $tree->tree();
        $stack = $items;

        $this->_navTree_ok( $stack );
    }


    /**
     *
     */
    public  function test_tree_children() {
        $n_children = 4;
        $tree = new  Navtree_Model_Tree('nav-parent');

        $items = $tree->tree();
        $this->assertEquals( self::n_children, count( $items ) );

        foreach ( $items as $item ) {
            $children = $item->children;
            $this->assertEquals( self::n_children, count($children) );
            $this->_navTree_ok($children);
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

            $this->assertTrue( $last_prio <= $schema_item->priority );
            $this->assertInstanceOf( 'Navtree_Model_Item', $schema_item );

            $last_prio = $schema_item->priority;
        }
    }


}
