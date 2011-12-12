<?php
class Pico_View_Admin_Page extends Pico_View_Admin_Base{
    public function get( Nano_App_Request $request, $config ){
        return $this->template()->render('page/list');
    }

    public function getEdit( $request, $config ){
        @list( , , , $id ) = $request->pathParts();

        $item = $this->model('Item', $id );

        $form = new Pico_Form_Item( $item );
        $this->template()->page = $item;
        $this->template()->form = $form;

        return $this->template()->render('page/edit');
    }

}
