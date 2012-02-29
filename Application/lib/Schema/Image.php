<?
class Pico_Schema_Image extends Nano_Db_Schema {
    protected $_tableName = 'item_content';

    protected $_schema = array(
        'id' => array(
            'type'      => 'int',
            'length'    => 10,
            'default'   => '',
            'name'      => 'id',
            'extra'     => 'auto_increment',
            'required'  => true,
        ),

        'item_id' => array(
            'type'      => 'int',
            'length'    => 10,
            'default'   => '',
            'name'      => 'item_id',
            'extra'     => '',
            'required'  => true,
        ),

        'value' => array(
            'type'      => 'text',
            'length'    => 0,
            'default'   => '',
            'name'      => 'value',
            'extra'     => '',
            'required'  => true,
        ),

        'draft' => array(
            'type'      => 'text',
            'length'    => 0,
            'default'   => '',
            'name'      => 'draft',
            'extra'     => '',
            'required'  => true,
        ),

        'html' => array(
            'type'      => 'text',
            'length'    => 0,
            'default'   => '',
            'name'      => 'html',
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

    private function _get_item(){
       return $this->has_a(array(
           'key'         => 'item_id',
           'table'       => 'item',
           'foreign_key' => 'id'
       ));
   }
            

}