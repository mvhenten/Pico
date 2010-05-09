<?php
class Pico_Router extends Pico_Collection{
    public function __construct( $routes ){
        parent::__construct( $this->getRoute( $routes ) );
    }

    private function getRequestUri(){
        return $_SERVER['REQUEST_URI'];
    }

    private function getRequestValues( $replace ){
        $url = $this->getRequestUri();
        $url = str_replace( $replace, '', $url );
        return array_filter(explode( '/', array_shift( explode( '?', $url ) )));
    }

    private function getRoute( $routes ){
        $request = $this->getRequestUri();
        $url    = '';
        $route  = $routes->default;

        foreach( $routes as $name => $value ){
            $url = $routes->$name;
            $url = preg_replace( '/\/:(\w+)/', '', $url['route'] );

            if( ! empty( $url )
               && preg_match( sprintf( '/%s/', str_replace('/', '\/', $url ) ), $request ) > 0 ){
                $route = $routes->$name;
                break;
            }
        }


        $n       = preg_replace( '/\/(\w+)/', '', $route['route'] );
        $keys    = array_filter( explode( '/:', $n ));
        $router  = array_combine( $keys, array_pad( array(), count($keys), Null ) );
        $router  = array_merge( $router, $route['defaults'] );
        $request = $this->getRequestValues( $url );

        while( count($request) > 0 ){
            while( count( $keys ) > 0 ){
                $key = array_shift( $keys );
                $value = array_shift( $request );
                if( null !== $value ){
                    $router[$key] = $value;
                }
            }
            $key = array_shift( $request );
            if( null !== $key ){
                $router[$key] = array_shift( $request );
            }
        }

        return $router;
    }
}

