<?php
class Pico_Model_Item extends Pico_Schema_Item {
    private function _filter_slug( $string ){
        $string = preg_replace('/\W/', '-',$string);

        

        $count = Nano_Db_Query::get('Item')
            ->where('id !=', $this->id )
            ->where('slug LIKE', sprintf('%s%%', $string))
            ->count();


        if( $count > 0 ){
            $string .= '-' . $count++;
        }

        return $string;
    }

}
