<?php
/**
 * Application/lib/Test/Autoload.php
 *
 * Test plumbing - call Pico_Test_Autoload::register()
 * to include Pico / Nano stack
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


ini_set('display_errors', "true");
ini_set('display_warnings', "true");

// the root of the application
define( "APPLICATION_ROOT", dirname( dirname( dirname(__FILE__) )));
//where the application is
define( "APPLICATION_PATH", dirname( APPLICATION_ROOT ));

require_once dirname(APPLICATION_PATH) . '/Nano/library/Nano/Autoloader.php';

class Pico_Test_Autoload {

    /**
     * Wraper around Nano_Autoloader - registers Nano and Pico namespaces
     */
    public static function register() {
        Nano_Autoloader::register();
        Nano_Autoloader::registerNamespace( 'Pico', APPLICATION_ROOT . '/lib' );
    }


}
