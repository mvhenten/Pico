<?php
/**
 * Application/lib/Helper/Items.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


class Pico_Helper_Items extends Nano_View_Helper{


    /**
     *
     *
     * @param unknown $type (optional)
     * @param array   $args (optional)
     * @return unknown
     */
    function Items( $type = null, array $args = array(), $visible = true ) {
        $model = new Pico_Model_Item();

        $args = array_merge( array(
                'where' => array(
                    'type' => $type,
                ),
                'order' => 'priority',
            ), $args );
        
        if( $visible ) {
            $args['where']['visible'] = 1;
        }

        return $model->search( $args );
    }


}
