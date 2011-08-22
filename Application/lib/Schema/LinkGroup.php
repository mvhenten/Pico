<?
class Pico_Schema_LinkGroup extends Nano_Db_Schema {
    protected $_tableName = 'link_group';

    protected $_schema = array(
        'id' => array(
            'type'      => 'int',
            'length'    => 10,
            'default'   => '',
            'name'      => 'id',
            'extra'     => 'auto_increment',
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
            'length'    => 1024,
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