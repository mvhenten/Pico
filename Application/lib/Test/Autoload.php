<?php
ini_set('display_errors', "true");
ini_set('display_warnings', "true");

define( "APPLICATION_ROOT", dirname( dirname( dirname(__FILE__) ))); // the root of the application
define( "APPLICATION_PATH", dirname( APPLICATION_ROOT )); //where the application is

require_once dirname(APPLICATION_PATH) . '/Nano/library/Nano/Autoloader.php';

class Pico_Test_Autoload {
    public static function register(){
        Nano_Autoloader::register();
        Nano_Autoloader::registerNamespace( 'Pico', APPLICATION_ROOT . '/lib' );        
    }
}