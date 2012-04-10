<?php
/**
 * t/Model/Block.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


require dirname(__FILE__) . '/../bootstrap.php';

class Builder_Model_BlockTest extends PHPUnit_Framework_TestCase{

    /**
     * Create a simple, dumbed down version of the table used
     * in sqlite sufficient for unit testing.
     */
    protected function setUp() {
        Nano_Db::setAdapter( array( 'dsn' => 'sqlite::memory:' ) );

        $dbh = Nano_Db::getAdapter();

        $dbh->query('
            CREATE TABLE "builder_block" (
              "id" INTEGER PRIMARY KEY NOT NULL,
              "parent_id" INTEGER NOT NULL DEFAULT 0,
              "type" TEXT NOT NULL,
              "data" TEXT NOT NULL,
              "updated" DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
              "inserted" DATETIME NOT NULL DEFAULT "0000-00-00 00:00:00"
            );
        ') or die('could not create db');
    }


    /**
     * Test insert
     */
    public function testCreate() {
        $expected_data =  (object) array( 'test' => 1 );

        $model = new Builder_Model_Block(array(
                'type'      => 'foo',
                'data'      => $expected_data,
                'parent_id' => 0
            ));

        $stored = $model->store();
        $this->assertType( 'Builder_Model_Block', $stored, 'Successfully stored object' );

        foreach ( array( 'parent_id', 'type', 'data' ) as $key ) {
            $this->assertEquals( $model->$key, $stored->$key, 'Stored value retrieved');
        }
    }


    /**
     * Test simple find
     */
    public function testFind() {
        $model =  $this->_createModel();
        $found = $model->find( array( 'id' => $model->id ) );

        $this->assertType( 'Builder_Model_Block', $found, 'Successfully retrieved object' );
    }


    /**
     * Test update using save
     */
    public function testUpdate() {
        $model =  $this->_createModel();

        $model->type      = 'bar';
        $model->parent_id = 99;
        $model->data      = (object) array( 'one' => 90, 'two' => 'test' );

        $model->save();
        $found = $model->find( array( 'id' => $model->id ) );

        foreach ( array( 'parent_id', 'id', 'type', 'data' ) as $key ) {
            $this->assertEquals( $model->$key, $found->$key, 'Child parent retrieved');
        }
    }


    /**
     * Test $model->parent();
     */
    public function testParent() {
        $parent = $this->_createModel();
        $child = $this->_createModel( $parent->id );

        $got_parent = $child->parent();

        foreach ( array( 'parent_id', 'id', 'type', 'data' ) as $key ) {
            $this->assertEquals( $parent->$key, $got_parent->$key, 'Child parent retrieved');
        }
    }


    /**
     * Test $model->children();
     */
    public function testChildren() {
        $parent = $this->_createModel();
        $n_children = 10;

        foreach ( range( 1, $n_children ) as $i ) {
            $this->_createModel( $parent->id );
        }

        $children = $parent->children()->fetchAll();
        $this->assertEquals( $n_children, count( $children ), 'Sucessfully retrieved children' );
    }


    /**
     * Create a model
     *
     * @param unknown $parent_id (optional)
     * @return unknown
     */
    private function _createModel( $parent_id = 0 ) {
        $model = new Builder_Model_Block(array(
                'type'      => 'foo',
                'data'      => (object) array( 'test' => 1 ),
                'parent_id' => $parent_id
            ));

        return $model->store();
    }


}
