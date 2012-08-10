<?php
/**
 * Application/t/Test/Db.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


class Pico_Test_DbTest extends PHPUnit_Framework_TestCase{

    /**
     * load pico/nano library
     */
    protected function setUp() {
        require_once '../../lib/Test/Autoload.php';
        Pico_Test_Autoload::register();
    }


    /**
     * init_db initializes sqlite, but no tables
     */
    public function test_init_db() {
        Pico_Test_Db::init_db();

        $dbh = Nano_Db::getAdapter();

        $sth = $dbh->query('SELECT name FROM sqlite_master WHERE type=\'table\'');
        $tables = $sth->fetchAll(PDO::FETCH_COLUMN);

        $this->assertEquals( array(), $tables );
    }



    /**
     * load_pico_schema initializes full empty db
     */
    public function test_load_pico_schema() {
        Pico_Test_Db::load_pico_schema();
        $dbh = Nano_Db::getAdapter();

        $expected = array(
            'image_data',
            'image_label',
            'item',
            'item_content',
            'link',
            'link_group',
            'setting'
        );


        $sth = $dbh->query('SELECT name FROM sqlite_master WHERE type=\'table\'');
        $tables = $sth->fetchAll(PDO::FETCH_COLUMN);

        $this->assertEquals( $expected, $tables );
    }


}
