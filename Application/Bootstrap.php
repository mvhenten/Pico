<?php
/**
 * Application/Bootstrap.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


ini_set('display_errors', "true");
ini_set('display_warnings', "true");
ini_set('upload_max_filesize', '16M');
ini_set('post_max_size', '16M');

define( "APPLICATION_ROOT", dirname(__FILE__) ); // the root of the application
define( "APPLICATION_PATH", dirname( APPLICATION_ROOT )); //where the application is
define( 'NANO_DEBUG', true );

require_once dirname(APPLICATION_PATH) . '/Nano/library/Nano/App.php';
//@TODO FIXME get rid of nano_config_ini
$config     = new Nano_Config_Ini( APPLICATION_ROOT . '/config/application.ini' );
$ini_config = parse_ini_file( APPLICATION_ROOT . '/config/application.ini', true );

Nano_App::Bootstrap(array(
        'namespace'   => array( 'Pico' => dirname(__FILE__) . '/lib' ),
        'config'      => $config,
        'plugins'     => isset($ini_config['plugins']) ? $ini_config['plugins'] : array(),
        'nano_db'     => $ini_config['database'],
        'router'   => array (
            '/image/\w+/\d+'               => 'Pico_View_Image',
            '/admin/image(/\w+)?(/\d+)?'   => 'Pico_View_Admin_Image',
            '/admin/label(/\w+)?(/\d+)?'   => 'Pico_View_Admin_Label',
            '/admin/page(/\w+)?(/\d+)?'    => 'Pico_View_Admin_Page',
            '/admin/content/\w+/\d+'       => 'Pico_View_Admin_Content',
            '/admin/user(/\w+)?'           => 'Pico_View_Admin_User',
            '/admin'                       => 'Pico_View_Admin_Image',
            '/(.+)?'                      => 'Pico_View_Index',
        ))
)->dispatch();
