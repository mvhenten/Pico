<?php
/**
 * Application/plugin/Navtree/t/Helper/Navigation.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


require_once basename(__FILE__) . '/../include.php';

class Pico_Test_testHelper_Navigation extends PHPUnit_Framework_TestCase{

    /**
     * Basic test, must return toplevel children.
     */
    public function test_Navigation() {
        $n_children = 3;

        $tree = Navtree_Test_Tree::create('fubar-tree', $n_children );

        $helper = new Navtree_Helper_Navigation(null);
        $items  = $helper->Navigation( 'fubar-tree' );

        $this->assertEquals( $n_children, count( $items ) );

        $this->_navtree_ok( $items );
    }


    /**
     * Test navigation children with depth, check url concatination.
     */
    public function test_Navigation_children() {
        $n_children = 3;

        $tree = Navtree_Test_Tree::create('fubar-tree2', $n_children );
        $helper = new Navtree_Helper_Navigation(null);
        $items  = $helper->Navigation( 'fubar-tree2', array( 'nav_parent_2' ) );

        $this->assertEquals( $n_children, count( $items ) );
        $this->_navtree_ok( $items );

        foreach ( $items as $item ) {
            $path_part = trim( $item->short_url, '/' );

            $this->assertRegExp( '{/[\w-]+/[\w-]+}', $item->url );

            if ( $path_part == 'nav_parent_2' ) {
                $this->assertTrue( $item->active );
            }
            else {
                $this->assertFalse( $item->active );
            }
        }
    }


    /**
     * Test level parameter, test if active is set
     */
    public function test_Navigation_active() {
        $n_children = 3;

        $tree = Navtree_Test_Tree::create('fubar-tree3', $n_children );
        $helper = new Navtree_Helper_Navigation(null);
        $items  = $helper->Navigation( 'fubar-tree3', array( 'nav_parent_2' ), 0 );

        $item_was_matched = false;

        foreach ( $items as $item ) {
            $path_part = trim( $item->short_url, '/' );
            $this->assertRegExp( '{/[\w-]+$}', $item->url );

            if ( $path_part == 'nav_parent_2' ) {
                $item_was_matched = true;
                $this->assertTrue( $item->active );
            }
            else {
                $this->assertFalse( $item->active );
            }
        }

        $this->assertTrue( $item_was_matched );
    }


    /**
     * Check the structure of ->children, must be an array of objects
     *
     * @param unknown $items
     */
    private function _navtree_ok( $items ) {
        foreach ( $items as $item ) {
            $this->assertObjectHasAttribute( 'url', $item );
            $this->assertObjectHasAttribute( 'name', $item );
            $this->assertObjectHasAttribute( 'short_url', $item );
            $this->assertObjectHasAttribute( 'active', $item );
        }

    }




}
