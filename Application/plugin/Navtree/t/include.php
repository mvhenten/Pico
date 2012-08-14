<?php
/**
 * Application/plugin/Navtree/t/include.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


define( 'PLUGIN_BASE', dirname(dirname(__FILE__)));
require_once PLUGIN_BASE . '/../../lib/Test/Autoload.php';


Pico_Test_Autoload::register();
Nano_Autoloader::registerNamespace( 'Navtree', PLUGIN_BASE  );

Pico_Test_Db::load_pico_schema();
