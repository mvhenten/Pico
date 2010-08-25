<?php
/**
 * class Model_Label
 * @package Pico_Model
 */
class Model_Label extends Model_Item{
    protected $_properties = array(
        'type'  => 3
    );

    public static function get( $key = null, $name = __CLASS__ ){
        return parent::get( $key, $name );
    }
}
