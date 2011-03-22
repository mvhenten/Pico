<?php
/**
 * class Model_Link
 * @package Pico_Model
 */
class Model_Setting extends Nano_Db_Model{
    const FETCH_TABLENAME   = 'setting';
    const FETCH_PRIMARY_KEY = 'id';

    public static function get( $key = null, $name = __CLASS__ ){
        return parent::get( $key, $name );
    }
}
