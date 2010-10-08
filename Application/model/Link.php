<?php
/**
 * class Model_Label
 * @package Pico_Model
 */
class Model_Link extends Nano_Db_Model{
    const FETCH_TABLENAME   = 'link';
    const FETCH_PRIMARY_KEY = 'id';

    protected $_properties = array(
    );

    public static function get( $key = null, $name = __CLASS__ ){
        return parent::get( $key, $name );
    }
}
