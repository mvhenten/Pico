<?php
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
        'image' =>
            array (
                'route' => '/image/:type/:id',
                'defaults' =>
                array (
                    'view' => 'image',
                    'action' => 'list',
                    'type' => 'image',
                    'id' => '1',
                ),
        ),
        'admin' =>
            array (
                'route' => '/admin/:view/:action/:id',
                'defaults' =>
                array (
                    'module' => 'admin',
                    'view' => 'image',
                    'action' => 'list',
                ),
        ),
        'site' =>
            array (
                'route' => '/:primary/:secondary',
                'defaults' =>
            array (
                'view' => 'index',
                'primary' => 'home',
                'secondary' => '',
        ),
  ),
)
))->dispatch();
