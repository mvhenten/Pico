<?
class Pico_Schema_Link extends Nano_Db_Schema {
    protected $_tableName = 'link';

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

        'parent_id' => array(
            'type'      => 'int',
            'length'    => 10,
            'default'   => '0000000000',
            'name'      => 'parent_id',
            'extra'     => '',
            'required'  => true,
        ),

        'priority' => array(
            'type'      => 'int',
            'length'    => 10,
            'default'   => '0',
            'name'      => 'priority',
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

        'url' => array(
            'type'      => 'varchar',
            'length'    => 4096,
            'default'   => '',
            'name'      => 'url',
            'extra'     => '',
            'required'  => true,
        ),

        'description' => array(
            'type'      => 'varchar',
            'length'    => 4096,
            'default'   => '',
            'name'      => 'description',
            'extra'     => '',
            'required'  => true,
        )
    );

    protected $_primary_key = array(
        'id'
    );



}