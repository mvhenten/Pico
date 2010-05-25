<?php
class Model_Item extends Pico_Model{
    protected $_id;
    protected $_name;
    protected $_description;
    protected $_type;
    protected $_visible;
    protected $_updated;
    protected $_inserted;
    protected $_data;

    protected $tableName = 'item';

    protected function getData(){
        if( null == $this->_data ){
            $this->find();
        }

        if( is_string( $this->_data ) ){
            $this->_data = json_decode(json_decode( stripslashes($this->_data) ));
        }


        //var_dump( $this->_data );
        //exit;

        return $this->_data;
    }

    protected function setData( $data ){
        $this->_data = json_encode( $data );
    }
}
