<?php
/**
 * Application/lib/Model/ItemContent.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Bison
 */


class Pico_Model_ItemContent extends Pico_Schema_ItemContent {

    /**
     *
     *
     * @param unknown $string
     * @return unknown
     */
    public function _filter_value( $string ) {
        return $string;
    }


    /**
     *
     *
     * @param unknown $string
     * @return unknown
     */
    public function _filter_draft( $string ) {
        return $string;
    }


    /**
     *
     *
     * @return unknown
     */
    public function item() {
        return $this->has_one( 'Pico_Model_Item', array( 'id' => 'item_id' ));
    }


}
