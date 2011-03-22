<?php
class Model_Item extends Nano_Db_Model{
    const FETCH_TABLENAME   = 'item';
    const FETCH_PRIMARY_KEY = 'id';

    public function getContent(){
        return Nano_Db_Query::get( 'ItemContent', array('item_id'=> $this->id));
    }
}
