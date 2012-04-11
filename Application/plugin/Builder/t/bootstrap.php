<?php
/**
 * t/bootstrap.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


define( 'NANO_ROOT', dirname(__FILE__) . '../../../../../../');
require_once NANO_ROOT . 'Nano/library/Nano/Autoloader.php';
Nano_Autoloader::register();

Nano_Autoloader::registerNamespace( 'Builder', dirname(__FILE__) . '/../lib'  );
