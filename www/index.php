<?php
error_reporting(E_ALL);
ini_set('display_errors', "true");
ini_set('display_warnings', "true");
ini_set('upload_max_filesize', '16M');
ini_set('post_max_size', '16M');

define( "APPLICATION_PATH", realpath('../') );
require_once( APPLICATION_PATH . '/Application/Bootstrap.php' );
$bootstrap = new Bootstrap();
?>
