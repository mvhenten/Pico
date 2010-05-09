<?php
# require_once( APPLICATION_PATH . '/library/Nano/Bootstrap/Abstract.php');
require_once( APPLICATION_PATH . '/Library/Nano/Autoloader.php');
class Bootstrap{
    private $_view;

    public function __construct(){

        Nano_Autoloader::register();
        Nano_Autoloader::registerNamespace( 'Nano', APPLICATION_PATH . '/Library/Nano' );
        Nano_Autoloader::registerNamespace( 'Pico', APPLICATION_PATH . '/Library/Pico' );
        Nano_Autoloader::registerNamespace( 'Model', APPLICATION_PATH . '/Application/model' );

        $config  = new Nano_Config_Ini( APPLICATION_PATH . '/Application/config/application.ini' );
        $router  = new Nano_Router( $config->route );
        $request = new Nano_Request( $router );

        Nano_Session::start();

        Nano_Db::setAdapter( $config->database );

        if( '' !== $router->module ){
            $module = ucfirst( $router->module );
            Nano_Autoloader::registerNamespace( 'Controller', APPLICATION_PATH . '/Application/modules/' . $module . '/controller' );
            $name = sprintf( 'Controller_%s', ucfirst($request->controller ));
            $controller = new $name( $request, $config );
            $controller->setLayout('admin');
        }
        else{
            Nano_Autoloader::registerNamespace( 'Controller', APPLICATION_PATH . '/Application/controller' );
            $name = sprintf( 'Controller_%s', ucfirst($request->controller ));
            $controller = new $name( $request, $config );
        }

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
