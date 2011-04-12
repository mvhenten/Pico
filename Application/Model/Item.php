<?php
class Model_Item extends Nano_Db_Model{
    const FETCH_TABLENAME   = 'item';
    const FETCH_PRIMARY_KEY = 'id';

    public function getContent(){
        return Nano_Db_Query::get( 'ItemContent', array('item_id'=> $this->id));
    }
    
    public function setSlug( $string ){
        $count = Nano_Db_Query::get('Item')
            ->where('id !=', $this->id )
            ->where('slug LIKE', sprintf('%s%%', $string))
            ->count();
            
        if( $count > 0 ){
            $string .= '-' . $count++;
        }
        
        $this->_properties['slug'] = $string;
        return $this;
    }
    
    public function setName( $string ){
        if( !mb_check_encoding($string, 'UTF-8') ){
            $string = mb_convert_encoding($string, 'UTF-8');
        }
        
        $this->_properties['name'] = $string;
    }
}
