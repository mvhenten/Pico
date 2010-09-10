<?php
/**
 * class Model_LinkGroup
 * @package Pico_Model
 */
class Model_LinkGroup extends Nano_Db_Model{
    const FETCH_TABLENAME   = 'link_group';
    const FETCH_PRIMARY_KEY = 'id';

    public static function get( $key = null, $name = __CLASS__ ){
        return parent::get( $key, $name );
    }
}
