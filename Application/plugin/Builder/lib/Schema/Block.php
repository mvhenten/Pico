<?php
class Builder_Schema_Block extends Nano_Db_Schema {
    protected $_tableName = 'builder_block';

    protected $_schema = array(
        'id' => array(
            'type'      => 'int',
            'length'    => 10,
            'default'   => '',
            'name'      => 'id',
            'extra'     => 'auto_increment',
            'required'  => true,
        ),

        'parent_id' => array(
            'type'      => 'int',
            'length'    => 10,
            'default'   => '',
            'name'      => 'parent_id',
            'extra'     => '',
            'required'  => true,
        ),

        'type' => array(
            'type'      => 'varchar',
            'length'    => 64,
            'default'   => '',
            'name'      => 'type',
            'extra'     => '',
            'required'  => true,
        ),

        'data' => array(
            'type'      => 'longblob',
            'length'    => 0,
            'default'   => '',
            'name'      => 'data',
            'extra'     => '',
            'required'  => true,
        ),

        'updated' => array(
            'type'      => 'timestamp',
            'length'    => 0,
            'default'   => '0000-00-00 00:00:00',
            'name'      => 'updated',
            'extra'     => 'on update CURRENT_TIMESTAMP',
            'required'  => true,
        ),

        'inserted' => array(
            'type'      => 'timestamp',
            'length'    => 0,
            'default'   => '0000-00-00 00:00:00',
            'name'      => 'inserted',
            'extra'     => '',
            'required'  => true,
        )
    );

    protected $_primary_key = array(
        'id'
    );
}
