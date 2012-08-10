<?php
/**
 * Application/plugin/Navtree/Model/Navtree.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


class Navtree_Model_Navtree extends Pico_Schema_Item {


    /**
     *
     *
     * @return unknown
     */
    function items() {
        $model = new Pico_Schema_Item();

        return $model->search(array(
                'where' => array(
                    'type' => 'navigation',
                ),
                'order' => 'parent'//array( 'parent', 'priority' )
            ));
    }


}
