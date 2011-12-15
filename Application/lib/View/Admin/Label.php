<?php
class Pico_View_Admin_Label extends Pico_View_Admin_Base{
    public function get( Nano_App_Request $request, $config ){
        $labels = $this->model('Item')->search(array(
            'where' => array('type' => 'label'),
            'order' => 'updated'
        ));

        $this->template()->labels = $labels;

        return $this->template()->render('image/labels');
    }

    public function getEdit( $request, $config ){
        @list( , , , $id ) = $request->pathParts();

        $label = $this->model('Item', $id );

        $form = new Pico_Form_Item( $label );
        $this->template()->item = $label;
        $this->template()->form = $form;

        return $this->template()->render('image/label');
    }
}
