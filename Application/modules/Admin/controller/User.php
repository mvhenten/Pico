<?php
class Controller_Admin_User extends Nano_Controller{
    public function preDispatch(){
        $this->getView()->disableTemplate();
    }

    public function indexAction(){
        if( ! Nano_Session::session()->auth ){
            $this->_redirect( '/admin/user/login' );
        }
        $this->getView()->content = "Hello World";
    }

    public function logoutAction(){
        Nano_Session::session()->auth = false;
        $this->_redirect( '/admin/user/login' );
    }

    public function loginAction(){
        $request = $this->getRequest();

        if( $request->isPost() ){
            $config = $this->getConfig()->admin;
            if( $config->username == $request->name && md5($request->password) == $config->password ){
                Nano_Session::session()->auth = true;
                $this->_redirect('/admin');
            }

        }
        else{
            $form = new Nano_Form();
            $form->addElements(array(
                'name'  => array(
                    'label' => 'Name:',
                ),
                'password'  => array(
                    'type'  => 'password',
                    'label' => 'Password:',
                ),
                'submit'    => array(
                    'type'  => 'submit',
                    'value' => 'login'
                )
            ));

            $this->getView()->disableLayout();

            echo $form;
            exit;

            $this->getView()->form = $form;
        }
    }
}
