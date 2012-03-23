<?php
/**
 * Application/plugin/Builder/lib/Block.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


class Builder_Block extends Builder_Abc {

    protected $_element;



    /**
     *
     *
     * @return unknown
     */
    public function as_string() {
        return (string) $this->element;
    }



    /**
     *
     *
     * @return unknown
     */
    protected function _build__element() {
        $div = new Nano_Element( 'div' );
        $div->setVertile();

        return $div;
    }


}
