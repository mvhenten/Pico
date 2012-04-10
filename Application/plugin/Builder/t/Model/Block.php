<?php
require(  dirname(__FILE__) . '/../bootstrap.php' );

class Builder_Model_BlockTest extends PHPUnit_Framework_TestCase{

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

    public function testCreate() {
        $model = new Builder_Model_Block(array(
            'type'      => 'foo',
            'data'      => (object) array( 'test' => 1 ),
            'parent_id' => 0
        ));

        $stored = $model->store();

        $this->assertType( 'Builder_Model_Block', $stored, 'Successfully stored object' );
    }

    public function testFind() {
        $model = new Builder_Model_Block(array(
            'type'      => 'foo',
            'data'      => (object) array( 'test' => 1 ),
            'parent_id' => 0
        ));

        $model->store();
        $found = $model->find( array( 'id' => 1 ) );

        $this->assertType( 'Builder_Model_Block', $found, 'Successfully retrieved object' );
    }

    public function testParent(){
        $parent = $this->_createModel();
        $child = $this->_createModel( $parent->id );

        $got_parent = $child->parent();

        foreach( array( 'parent_id', 'id', 'type', 'data' ) as $key ){
            $this->assertEquals( $parent->$key, $got_parent->$key, 'Child parent retrieved');
        }
    }

    public function testChildren() {
        $parent = $this->_createModel();
        $n_children = 10;

        foreach( range( 1, $n_children ) as $i ) {
            $this->_createModel( $parent->id );
        }

        $children = $parent->children()->fetchAll();
        $this->assertEquals( $n_children, count( $children ), 'Sucessfully retrieved children' );
    }

    private function _createModel( $parent_id = 0 ){
        $model = new Builder_Model_Block(array(
            'type'      => 'foo',
            'data'      => (object) array( 'test' => 1 ),
            'parent_id' => $parent_id
        ));

        return $model->store();
    }


}
