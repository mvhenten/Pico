<?php
/**
 * Application/plugin/Navtree/Model/Item.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


class Navtree_Model_Item extends Pico_Schema_Item {


    /**
     * Return all items that are children of this item
     *
     * @return PdoStatement $items
     */
    function items() {
        return $this->has_many( 'Pico_Schema_Item', array(
                'type' => 'type'
            ), array(
                'order' => array( 'parent', 'priority' )
            ));

    }


}
