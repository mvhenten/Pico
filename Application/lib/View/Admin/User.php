<?php
/**
 * Application/lib/View/Admin/User.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Bison
 */


class Pico_View_Admin_User extends Nano_App_View{

    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     */
    public function post( Nano_App_Request $request, $config ) {
        @list( , , $action, $id ) = $request->pathParts();

        $config = $config['config'];

        if ( $action !== 'login' ) {
            $this->response()->redirect( '/admin/user/login' );
        }

        $post = $request->post;

        if ( $post['username'] == $config->admin->username ) {

            if ( crypt($post['password'], $config->admin->password) == $config->admin->password ) {
                @session_start();
                $_SESSION['user'] = 1;
                $this->response()->redirect( '/admin/' );
            }
        }

        $this->response()->redirect( '/admin/user/login?error=1' );
    }


    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     * @return unknown
     */
    public function get( Nano_App_Request $request, $config ) {
        @list( , , $action ) = $request->pathParts();

        if ( $action == 'login' ) {
            return $this->login( $request, $config );
        }

        if ( $action == 'logout' ) {
            return $this->logout( $request, $config );
        }

        $this->response()->redirect( '/' );
    }


    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     * @return unknown
     */
    public function login( Nano_App_Request $request, $config ) {
        @session_start();

        if ( isset($_SESSION['user']) ) {
            $this->response()->redirect( '/admin/' );
        }

        $this->template()->templatePath = 'Application/Admin/template';
        return $this->template()->render('user');
    }



    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     */
    public function logout( Nano_App_Request $request, $config ) {
        @session_start();

        session_destroy();


        $this->response()->redirect( '/admin/user/login' );
    }


}
