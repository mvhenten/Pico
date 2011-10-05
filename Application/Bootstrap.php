<?php
define( "APPLICATION_ROOT", dirname(__FILE__) ); // the root of the application
define( "APPLICATION_PATH", dirname( APPLICATION_ROOT )); //where the application is
require_once( dirname(APPLICATION_PATH) . '/Nano/library/Nano/Autoloader.php');

class Bootstrap{
    public function __construct(){

        Nano_Autoloader::register();

        $config  = new Nano_Config_Ini( APPLICATION_ROOT . '/config/application.ini' );
        $router  = new Nano_Router( $config->route );
        $request = new Nano_Request( $router );

        Nano_Db::setAdapter( $config->database );
        Nano_Session::start();

        Nano_Autoloader::registerNamespace( 'Pico', APPLICATION_ROOT . '/lib');

        if( null !== $router->module ){
            Nano_Autoloader::registerNamespace( 'Form',
                APPLICATION_ROOT . '/' . ucfirst($router->module) . '/Forms' );
        }

        $klass = array($request->module ? $request->module : 'Pico', 'View', $request->view );
        $klass = join( '_', array_map('ucfirst', array_filter($klass)));

        if( class_exists($klass) ){
            $view = new $klass( $request, $config );
            $view->template()->addHelperPath( 'admin/helper' );
            $view->response()->out();
        }

    }
}
