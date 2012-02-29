<?php
/**
 * Application/lib/Helper/Items.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Bison
 */


class Pico_Helper_Items extends Nano_View_Helper{


    /**
     *
     *
     * @param unknown $type (optional)
     * @return unknown
     */
    function Items( $type = null ) {
        $model = new Pico_Model_Item();

        return $model->search(array(
                'where' => array('type' => $type, 'visible' => 1 ),
                'order' => 'updated'
            ));
    }


}
