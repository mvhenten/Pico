<?php
define( "APPLICATION_ROOT", dirname(__FILE__) ); // the root of the application
define( "APPLICATION_PATH", dirname( APPLICATION_ROOT )); //where the application is
require_once( APPLICATION_PATH . '/Library/Nano/Autoloader.php');

class Bootstrap{
    public function __construct(){

        Nano_Autoloader::register();
        Nano_Autoloader::registerNamespace( 'Nano', APPLICATION_PATH . '/Library/Nano' );
        Nano_Autoloader::registerNamespace( 'Pico', APPLICATION_PATH . '/Library/Pico' );//?
        Nano_Autoloader::registerNamespace( 'Model', APPLICATION_ROOT . '/model' );

        $config  = new Nano_Config_Ini( APPLICATION_ROOT . '/config/application.ini' );
        $router  = new Nano_Router( $config->route );
        $request = new Nano_Request( $router );

        Nano_Db::setAdapter( $config->database );
        Nano_Session::start();

        if( null !== $router->module ){
            Nano_Autoloader::registerNamespace( 'Controller_Admin', APPLICATION_ROOT . '/' . $router->module . '/controller' );
            Nano_Autoloader::registerNamespace( 'Form', APPLICATION_ROOT . '/' . $router->module . '/forms' );
        }
        else{
            Nano_Autoloader::registerNamespace( 'Controller', APPLICATION_ROOT . '/controller' );
        }

        $klass = array('Controller', $request->module, $request->controller );
        $klass = join( '_', array_map('ucfirst', array_filter($klass)));

        if( class_exists($klass) ){
            $view = new $klass( $request, $config );
            $view->template()->addHelperPath( 'admin/helper' );
            $view->response()->out();
        }

    }
}
