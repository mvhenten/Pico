<?php
/**
 * Application/lib/View/Admin/Label.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Bison
 */


class Pico_View_Admin_Label extends Pico_View_Admin_Base{

    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     * @return unknown
     */
    public function get( Nano_App_Request $request, $config ) {
        $labels = $this->model('Item')->search(array(
                'where' => array('type' => 'label'),
                'order' => 'updated'
            ));

        $this->template()->labels = $labels;

        return $this->template()->render('label/list');
    }


    /**
     *
     *
     * @param unknown $request
     * @param unknown $config
     * @return unknown
     */
    public function getEdit( $request, $config ) {
        @list( , , , $id ) = $request->pathParts();

        if ( !$request->isPost() ) {
            $label = $this->model('Item', $id );

            $form = new Pico_Form_Item( $label );
            $this->template()->item = $label;
            $this->template()->form = $form;
        }

        return $this->template()->render('label/edit');
    }


}
