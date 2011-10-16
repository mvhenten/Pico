<?php
class Pico_Helper_Images extends Nano_View_Helper{
    function Images( $label = null ){
        if( null != $label ){
            return $this->_imagesFromLabel($label);
        }

        $search = new Pico_Model_Item();

        return $search->search(array(
            'where' => array('type' => 'image'),
            'order' => '-inserted'));
    }

    function _imagesFromLabel( $label ){
        if( is_numeric( $label ) ){
            $label = new Pico_Model_Item( $label );
        }
        else if( is_string( $label ) ){
            $labels = new Pico_Model_Item();
            $label = $labels->search(array('where' => array('slug' => $label )));
        }

        if( $label  instanceof Pico_Model_Item ){
            return $label->images();
        }
    }
}
