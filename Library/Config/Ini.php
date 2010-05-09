<?php
class Pico_Config_Ini extends Pico_Collection{
    private $config = array();

    public function __construct( $path ){
        parent::__construct( parse_ini_file( $path, true ) );
        $this->init();
    }

    private function init(){
        foreach( $this as $section => $values ){
            $collect = array();
            foreach( $values as $key => $value ){
                $key    = explode( '.', $key );
                $key[]  = $value;
                $key    = $this->fold( $key );
                $collect = array_merge_recursive( $collect, $key );
            }
            $this[$section] = new Pico_Collection( $collect );
        }
    }

    private function fold( $values ){
        $key = array_shift( $values );
        if( $values ){
            $key = array(strtolower($key) => $this->fold( $values ));
        }
        return $key;
    }
}
