<?php
class Pico_Helper_Items extends Nano_View_Helper{
    function Items( $type = null ){
        $model = new Pico_Model_Item();

        return $model->search(array(
            'where' => array('type' => $type ),
            'order' => 'updated'
        ));
    }
}
