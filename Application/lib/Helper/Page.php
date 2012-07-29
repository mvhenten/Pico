<?php
/**
 * Application/lib/Helper/Items.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Bison
 */


class Pico_Helper_Page extends Nano_View_Helper{


    /**
     *
     *
     * @param unknown $type (optional)
     * @param array   $args (optional)
     * @return unknown
     */
    function Page( $slug ) {
        $model  = new Pico_Model_Item();
        $models = $model->search(array('slug' => $slug, 'visible' => 1, 'type' => 'page'));
        
        foreach( $models as $model ){
            return $model;
        }
    }


}
