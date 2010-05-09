<?php
class Pico_Collection extends ArrayObject{
    static function factory( $values = null ){
        return new Pico_Collection( (array) $values );
    }

    public function __get( $name ){
        $method = '';
        if( ( $method = 'get' . ucfirst( $name ) ) && method_exists( $this, $method ) ){
            return $this->$method();
        }
        if( $this->offsetExists( $name ) ){
            return $this->offsetGet( $name );
        }
    }

    public function __set( $name, $value ){
        $method = '';
        if( ( $method = sprintf( 'set%s', ucfirst( $name ) ) && method_exists( $this, $method ) ) ){
            call_user_func( array( $this, $method ), $value );
        }
        $this->offsetSet( $name, $value );
    }

    public function delete( $name ){
        if( $this->offsetExists( $name ) ){
            $value = $this->offsetGet( $name );
            $this->offsetUnset( $name );
            return $value;
        }
        return false;
    }
}
