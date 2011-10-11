<?php
class Admin_View_Base extends Nano_View{
    public function __construct( $request, $config ){
        // awful hacks here, for now.
        @session_start();

        if( !isset( $_SESSION['user'] ) ){
            $this->response()->redirect( '/admin/user/login' );
        }

        $this->template()->request = $request;
        $this->template()->templatePath = 'Application/Admin/template';

        parent::__construct( $request, $config );
    }
}
