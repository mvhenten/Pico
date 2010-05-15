<?php
class Pico_AdminController extends Nano_Controller{
    const ITEM_TYPE_IMAGE = 1;
    const ITEM_TYPE_PAGE  = 2;
    const ITEM_TYPE_LABEL = 3;
    const ITEM_TYPE_NAV   = 4;
    const ITEM_TYPE_CAT   = 5;

    public function preDispatch(){
        if( ! Nano_Session::session()->auth ){
            $this->_redirect( '/admin/user/login' );
        }
    }

    public function postDispatch(){
        $this->getView()->actions = $this->getMenu();
    }

    protected function getMenu(){
        return array();
    }

    protected function getItemForm( $item ){
        $form = new Nano_Form();

        $form->addElements(array(
            'type'  => array(
                'type'  => 'hidden',
                'value' => $item->type,
            ),
            'name' => array(
                'type'  => 'text',
                'value' => $item->name,
                'label' => 'Title'
            ),
            'description'   => array(
                'type'  => 'textarea',
                'value' => $item->description,
                'label' => 'Description'
            ),
            'visible'   => array(
                'type'  => 'checkbox',
                'value' => $item->visible,
                'label' => 'Visible'
            ),
            'submit'    => array(
                'type'  => 'submit',
                'value' => 'Save'
            )

        ));

        return $form;
    }
}
