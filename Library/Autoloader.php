<?php
class Pico_Autoloader{
    private static $instance;
    private $namespaces = array();

    private function __construct(){
    }

    public static function register(){
        spl_autoload_register( 'Pico_Autoloader::autoLoad' );
    }

    public static function getInstance(){
        if( null === self::$instance ){
            self::$instance = new Pico_AutoLoader();
        }

        return self::$instance;
    }

    public static function autoLoad( $name ){
        self::getInstance()->load( $name );
    }

    public static function registerNamespace( $name, $path ){
        self::getInstance()->addNamespace( $name, $path );
    }

    private function addNamespace( $name, $path ){
        $this->namespaces[$name] = $path;
    }

    private function load( $name ){
        $pieces = explode( '_', $name );
        $namespace = array_shift( $pieces );
        foreach( $this->namespaces as $key => $value ){
            if( $key == $namespace ){
                $path = sprintf( '%s/%s.php', $value, join( '/', $pieces ));
                $this->includePath( $path );
            }
        }
    }

    private function includePath( $path ){
        if( file_exists( $path ) ){
            require_once( $path );
        }
        else{
            throw new Exception( sprintf( 'File does not exist "%s"', $path ));
        }
    }





}
