<?php
class Controller_Index extends Nano_Controller{
    public function indexAction(){
        if( ! Nano_Session::session()->auth ){
            $this->_redirect( '/admin/login' );
        }

        $this->getView()->content = "Hello World";
    }
}
