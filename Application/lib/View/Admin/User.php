<?php
class Pico_View_Admin_User extends Nano_App_View{
    public function post( $request, $config ){
        $post = $request->getPost();

        if( $post->username != $config->admin->username ){
            $this->response()->redirect( '/admin/user/login?error=1' );
        }

        if( crypt( $post->password, $config->admin->password ) == $config->admin->password ){
            @session_start();
            $_SESSION['user'] = 1;
            $this->response()->redirect( '/admin/' );
        }

        $this->response()->redirect( '/admin/user/login?error=1' );
    }

    public function get( $request, $config ){
        @session_start();

        if( $request->action == 'logout' ){
            @session_destroy();

            $this->response()->redirect( '/admin/user/login' );
        }

        if( isset($_SESSION['user']) ){
            $this->response()->redirect( '/admin/' );
        }

        return $this->template()->render( APPLICATION_ROOT . '/Admin/template/user');
    }

}
