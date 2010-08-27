<?php
/**
 * class Model_Image
 * @package Pico_Model
 */
class Model_ImageLabel extends Nano_Db_Model{
    const FETCH_TABLENAME   = 'image_label';
    const FETCH_PRIMARY_KEY = '__NONE__';

    public static function get( $key = null, $name = __CLASS__ ){
        return parent::get( $key, $name );
    }
}
