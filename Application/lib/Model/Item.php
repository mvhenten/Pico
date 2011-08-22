<?php
class Pico_Model_Item extends Pico_Schema_Item {
    public function _filter_slug( $string ){
        $string = preg_replace('/[\W_]/', '-',$string);

        //@FIXME!

        //$item = new Pico_Model_Item();
        //
        //$items = $item->search( array( 'where' => array(
        //    'id'    => array( '!=', $this->id),
        //    'slug'  => array( 'LIKE', sprintf('%s%%', $string))
        //)));
        //
        //if( $items->rowCount() > 0 ){
        //    $string .= '-' . $items->rowCount() + 1;
        //
        //}

        return $string;
    }

}
