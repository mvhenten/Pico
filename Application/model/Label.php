<?php
class Model_Label extends Model_Item{
    public function __construct( $args = array() ){
        $this->_type = self::ITEM_TYPE_LABEL;
        parent::__construct( $args );
    }

    public function fetchImages( $limit = 10, $offset = 0 ){
        $search = new Model_ImageLabel();

        $search->labelId = $this->_id;
        $search->setLimit( $limit );
        $search->setOffset( $offset );

        return $search->find();
    }
}
