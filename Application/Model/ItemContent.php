<?php
/**
 * class Model_ItemContent
 * @package Pico_Model
 */
class Model_ItemContent extends Nano_Db_Model{
    const FETCH_TABLENAME   = 'item_content';
    const FETCH_PRIMARY_KEY = 'id';

    public static function get( $key = null, $name = __CLASS__ ){
        return parent::get( $key, $name );
    }
    
    public function __toString(){
        return (string) $this->value;
    }
}
