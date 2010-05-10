<?php
class Model_ImageData extends Pico_Model{
    protected $_id;
    protected $_imageId;
    protected $_width;
    protected $_height;
    protected $_type;
    protected $_mime;
    protected $_filename;
    protected $_data;

    protected $tableName = 'image_data';
}
