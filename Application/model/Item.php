<?php
class Model_Item extends Nano_Db_Model{
    const FETCH_TABLENAME   = 'item';
    const FETCH_PRIMARY_KEY = 'id';

    public static function get( $key = null, $name = __CLASS__ ){
        return parent::get( $key, $name );
    }
}
