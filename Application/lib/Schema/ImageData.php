<?php
class Pico_Schema_ImageData extends Nano_Db_Schema {
    protected $_tableName = 'image_data';

    protected $_schema = array(
        'id' => array(
            'type'      => 'int',
            'length'    => 10,
            'default'   => '',
            'name'      => 'id',
            'extra'     => 'auto_increment',
            'required'  => true,
        ),

        'image_id' => array(
            'type'      => 'int',
            'length'    => 10,
            'default'   => '',
            'name'      => 'image_id',
            'extra'     => '',
            'required'  => true,
        ),

        'size' => array(
            'type'      => 'int',
            'length'    => 10,
            'default'   => '',
            'name'      => 'size',
            'extra'     => '',
            'required'  => true,
        ),

        'width' => array(
            'type'      => 'int',
            'length'    => 10,
            'default'   => '',
            'name'      => 'width',
            'extra'     => '',
            'required'  => true,
        ),

        'height' => array(
            'type'      => 'int',
            'length'    => 10,
            'default'   => '',
            'name'      => 'height',
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

        'mime' => array(
            'type'      => 'varchar',
            'length'    => 255,
            'default'   => '',
            'name'      => 'mime',
            'extra'     => '',
            'required'  => true,
        ),

        'filename' => array(
            'type'      => 'varchar',
            'length'    => 1024,
            'default'   => '',
            'name'      => 'filename',
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

        'created' => array(
            'type'      => 'timestamp',
            'length'    => 0,
            'default'   => '0000-00-00 00:00:00',
            'name'      => 'created',
            'extra'     => 'on update CURRENT_TIMESTAMP',
            'required'  => true,
        )
    );

    protected $_primary_key = array(
        'id'
    );

    private function _get_image(){
       return $this->has_a(array(
           'key'         => 'image_id',
           'table'       => 'image',
           'foreign_key' => 'id'
       ));
   }
            
}
