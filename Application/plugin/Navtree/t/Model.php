<?php
/**
 * Application/plugin/Navtree/t/Model.php
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
        require_once  $plugin_base . '/Model/Navtree.php';

        $dbh = Nano_Db::getAdapter();
        $dbh->query( 'INSERT INTO item ( id, slug, type ) VALUES ( 1, "nav", "navigation")');

        $parents = range(2, 4);

        foreach ( $parents as $pri => $parent ) {
            $slug = join('_', array('parent', $pri, $parent) );
            $item = new Pico_Model_Item(array(
                    'parent' => 1,
                    'slug' => $slug,
                    'type' => 'navigation',
                    'priority' => $pri,
                    'id' => $parent
                ));

            $item->store();
        }

        foreach ( $parents as $pri => $parent ) {
            $slug = join('_', array('child', $pri, $parent) );
            $item = new Pico_Model_Item(array(
                    'parent' => $parent,
                    'slug' => $slug,
                    'type' => 'navigation',
                    'priority' => $pri  ,
                    'id' => $parent + 10
                ));

            $item->store();
        }
    }


    /**
     *
     */
    public function test_construct() {
        $tree = new  Navtree_Model_Navtree(1);

        foreach ( $tree->items() as $item ) {
            var_dump( $item->slug );
            $this->assertInstanceOf( 'Pico_Schema_Item', $item );
        }
    }


}
