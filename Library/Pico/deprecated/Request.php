<?php
class Pico_Request{
    private $_request;
    private $_post;
    private $_get;
    private $_router;

    public function __construct( Pico_Router $router = null ){
        if( null !== $router ){
            $this->_router = $router;
        }
    }

    public function __get( $name ){
        if( null !== $this->_router && null !== $this->_router->$name ){
            return $this->_router->$name;
        }
        elseif( null !== $this->getPost() && null !== $this->_post->$name ){
            return $this->_post->$name;
        }

        return $this->getRequest()->$name;
    }

    public function isPost(){
        if( count( $_POST ) > 0 ){
            return true;
        }
        return false;
    }

    public function getPost(){
        if( $this->isPost() && null == $this->_post ){
            $this->_post = new Pico_Collection( $_POST );
        }

        return $this->_post;
    }

    public function getRequest(){
        if( null == $this->_request ){
            $this->_request = new Pico_Collection( $_REQUEST );
        }
        return $this->_request();
    }
}
