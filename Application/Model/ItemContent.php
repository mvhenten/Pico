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

    public function __set( $name, $value ){
        //$value = str_replace(
        //array("\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x93", "\xe2\x80\x94", "\xe2\x80\xa6"),
        //array("'", "'", '"', '"', '-', '--', '...'),
        //$value);
        //// Next, replace their Windows-1252 equivalents.
        //$value = str_replace(
        //array(chr(145), chr(146), chr(147), chr(148), chr(150), chr(151), chr(133)),
        //array("'", "'", '"', '"', '-', '--', '...'), $value);
        $value = strip_tags( $value, '<br/><h3></h3><p></p>');
        $value = nl2br($value);

        parent::__set( $name, $value );
    }
}
