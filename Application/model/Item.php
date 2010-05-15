<?php
class Model_Item extends Pico_Model{
    protected $_id;
    protected $_name;
    protected $_description;
    protected $_type;
    protected $_visible;
    protected $_updated;
    protected $_inserted;

    protected $tableName = 'item';
}
