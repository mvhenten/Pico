<?php
define( "APPLICATION_ROOT", dirname(__FILE__) ); // the root of the application
define( "APPLICATION_PATH", dirname( APPLICATION_ROOT )); //where the application is

# require_once( APPLICATION_PATH . '/library/Nano/Bootstrap/Abstract.php');
require_once( APPLICATION_PATH . '/Library/Nano/Autoloader.php');
//@todo Fix APPLICATION_PATH or the Application prefix somewhere.
class Bootstrap{
    private $_view;

    public function __construct(){

        Nano_Autoloader::register();
        Nano_Autoloader::registerNamespace( 'Nano', APPLICATION_PATH . '/Library/Nano' );
        Nano_Autoloader::registerNamespace( 'Pico', APPLICATION_PATH . '/Library/Pico' );//?
        Nano_Autoloader::registerNamespace( 'Model', APPLICATION_ROOT . '/model' );

        $test = new Nano_Config_Ini( APPLICATION_ROOT . '/plugin/Caroussel/config.ini' );

        //var_dump( $test->plugin->my_value );
        //
        //exit();

        $config  = new Nano_Config_Ini( APPLICATION_ROOT . '/config/application.ini' );
        $router  = new Nano_Router( $config->route );
        $request = new Nano_Request( $router );

        Nano_Session::start();

        Nano_Registry::add( 'config', $config );
        Nano_Registry::add( 'router', $router );
        Nano_Registry::add( 'request', $request );

        Nano_Db::setAdapter( $config->database );

        if( null !== $router->module ){
            $module = $router->module;
            Nano_Autoloader::registerNamespace( 'Controller_Admin', APPLICATION_ROOT . '/' . $module . '/controller' );
            Nano_Autoloader::registerNamespace( 'Form', APPLICATION_ROOT . '/' . $module . '/forms' );

            $name = sprintf( 'Controller_%s_%s', ucfirst($module), ucfirst($request->controller ));
            $controller = new $name( $request, $config );
            //$controller->setLayout('admin');
            //$controller->setHelperPath( APPLICATION_ROOT . '/Admin/helper' );
            $controller->template()->addHelperPath( 'admin/helper' );

        }
        else{
            Nano_Autoloader::registerNamespace( 'Controller', APPLICATION_ROOT . '/controller' );
            $name = sprintf( 'Controller_%s', ucfirst($request->controller ));
            $controller = new $name( $request, $config );
        }

        //$controller->dispatch();

        $controller->response()->out();


        //$controller->template()->menu = '';

        //if( '' == $router->module ){
        //    $base = ucfirst( $router->module );
        //    Nano_Autoloader::registerNamespace( $base, APPLICATION_PATH . '/' . $base );
        //    //$name = sprintf( '%s_%s', ucfirst( $router->module ), $name );
        //}
        //else{
        //    $templatePath = APPLICATION_PATH . '/template';
        //    Nano_Autoloader::registerNamespace( 'Controller', APPLICATION_PATH . '/Controller' );
        //}


    }
}
