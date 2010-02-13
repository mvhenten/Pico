<?php
class Pico_Controller_Admin extends Pico_Controller{
    protected function init(){
        if( !Pico_Admin::hasIdentity() ){
            $this->redirect( '/' );
        }
    }
}
