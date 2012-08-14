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
     *
     */
    public function test_Navigation() {
        $tree = Navtree_Test_Tree::create('fubar-tree');

        $helper = new Navtree_Helper_Navigation(null);
        $items  = $helper->Navigation( 'fubar-tree' );

        foreach ( $items as $item ) {
            $this->assertType( 'Navtree_Model_Item', $item );
        }
    }


    /**
     *
     */
    public function test_Navigation_parent() {
        $tree = Navtree_Test_Tree::create('fubar-tree2');

        $helper = new Navtree_Helper_Navigation(null);
        $items  = $helper->Navigation( 'fubar-tree2', '/nav_parent_2');

        $this->_navtree_ok( $items );

        foreach ( $items as $item ) {
            $children = $helper->Navigation( 'fubar-tree2', $item->url() );
            $this->_navtree_ok( $children );
        }
    }


    /**
     *
     *
     * @param unknown $items
     */
    private function _navtree_ok( $items ) {
        foreach ( $items as $item ) {
            $this->assertType( 'Navtree_Model_Item', $item );
        }

    }




}
