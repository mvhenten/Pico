<?php
class Pico_Model_ItemContent extends Pico_Schema_ItemContent {
    public function _filter_value( $string ){
        return $string;
    }

    public function _filter_draft( $string ){
        return $string;
    }

    public function item(){
        return $this->has_one( 'Pico_Model_Item', array( 'id' => 'item_id' ));
    }
}
