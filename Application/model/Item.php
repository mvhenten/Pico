<?php
class Model_Item extends Nano_Db_Model{
    const FETCH_TABLENAME   = 'item';
    const FETCH_PRIMARY_KEY = 'id';
    
    const P_ITEM_TYPE_IMAGE = 1;
    const P_ITEM_TYPE_PAGE  = 2;
    const P_ITEM_TYPE_LABEL = 3;

    public static function get( $key = null, $name = __CLASS__ ){        
        return parent::get( $key, $name );
    }
}
