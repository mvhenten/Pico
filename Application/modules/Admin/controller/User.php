<?php
class Admin_Controller_User extends Pico_Controller_Admin{
    protected function loginAction(){
        if( Pico_Admin::hasIdentity() ){
            $this->forward( 'profile' );
            return;
        }
    }

    protected function profileAction(){
        echo "HELLO PROFILE";
    }
}
