<?php
class Pico_Schema_Item extends Nano_Db_Schema {
    protected $_tableName = 'item';

    protected $_schema = array(
        'id' => array(
            'type'      => 'int',
            'length'    => 10,
            'default'   => '',
            'name'      => 'id',
            'extra'     => 'auto_increment',
            'required'  => true,
        ),

        'slug' => array(
            'type'      => 'varchar',
            'length'    => 64,
            'default'   => '',
            'name'      => 'slug',
            'extra'     => '',
            'required'  => true,
        ),

        'type' => array(
            'type'      => 'varchar',
            'length'    => 255,
            'default'   => '',
            'name'      => 'type',
            'extra'     => '',
            'required'  => true,
        ),

        'priority' => array(
            'type'      => 'int',
            'length'    => 11,
            'default'   => '0',
            'name'      => 'priority',
            'extra'     => '',
            'required'  => true,
        ),

        'parent' => array(
            'type'      => 'int',
            'length'    => 10,
            'default'   => '',
            'name'      => 'parent',
            'extra'     => '',
            'required'  => true,
        ),

        'visible' => array(
            'type'      => 'tinyint',
            'length'    => 4,
            'default'   => '',
            'name'      => 'visible',
            'extra'     => '',
            'required'  => true,
        ),

        'name' => array(
            'type'      => 'varchar',
            'length'    => 255,
            'default'   => '',
            'name'      => 'name',
            'extra'     => '',
            'required'  => true,
        ),

        'description' => array(
            'type'      => 'varchar',
            'length'    => 2048,
            'default'   => '',
            'name'      => 'description',
            'extra'     => '',
            'required'  => true,
        ),

        'appendix' => array(
            'type'      => 'longblob',
            'length'    => 0,
            'default'   => '',
            'name'      => 'appendix',
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
