<?php

class Navtree_Model_Item extends Pico_Schema_Item {

    function items() {
        return $this->has_many( 'Pico_Schema_Item', array(
                'type' => 'type'
            ), array(
                'order' => array( 'parent', 'priority' )
            ));

    }


}