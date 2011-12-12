<?php
ini_set('display_errors', "true");
ini_set('display_warnings', "true");
ini_set('upload_max_filesize', '16M');
ini_set('post_max_size', '16M');

define( "APPLICATION_ROOT", dirname(__FILE__) ); // the root of the application
define( "APPLICATION_PATH", dirname( APPLICATION_ROOT )); //where the application is

require_once( dirname(APPLICATION_PATH) . '/Nano/library/Nano/App.php');
$config  = new Nano_Config_Ini( APPLICATION_ROOT . '/config/application.ini' );

Nano_App::Bootstrap(array(
    'namespace'   => array( 'Pico' => dirname(__FILE__) . '/lib' ),
    'config'      => $config,
    'nano_db'     => array(
        'dsn'       => $config->database->dsn,
        'username'  => $config->database->username,
        'password'  => $config->database->password,
    ),
    'router'   => array (
        '/image/\w+/\d+'    => 'Pico_View_Image',
        '/admin/image(/\d+)?' => 'Pico_View_Admin_Image',
        '/(\w+)?'             => 'Pico_View_Index',
  ))
)->dispatch();
