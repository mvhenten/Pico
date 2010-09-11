<?php
/**
 * class Model_Page
 * @package Pico_Model
 */
class Model_Page extends Model_Item{
    protected $_properties = array(
        'type'  => 2
    );

    public static function get( $key = null, $name = __CLASS__ ){
        return parent::get( $key, $name );
    }
}
