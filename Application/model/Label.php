<?php
class Model_Label extends Model_Item{
    public function fetchImages( $limit = 10, $offset = 0 ){
        $search = new Model_ImageLabel();

        $search->labelId = $this->_id;
        $search->setLimit( $limit );
        $search->setOffset( $offset );

        return $search->search();
    }
}
