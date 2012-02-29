<?php
class Pico_Schema_Setting extends Nano_Db_Schema {
    protected $_tableName = 'setting';

    protected $_schema = array(
        'id' => array(
            'type'      => 'int',
            'length'    => 10,
            'default'   => '',
            'name'      => 'id',
            'extra'     => 'auto_increment',
            'required'  => true,
        ),

        'group' => array(
            'type'      => 'varchar',
            'length'    => 255,
            'default'   => '',
            'name'      => 'group',
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

        'title' => array(
            'type'      => 'varchar',
            'length'    => 255,
            'default'   => '',
            'name'      => 'title',
            'extra'     => '',
            'required'  => true,
        ),

        'description' => array(
            'type'      => 'varchar',
            'length'    => 1024,
            'default'   => '',
            'name'      => 'description',
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

        'value' => array(
            'type'      => 'varchar',
            'length'    => 255,
            'default'   => '',
            'name'      => 'value',
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
        )
    );

    protected $_primary_key = array(
        'id'
    );


}
