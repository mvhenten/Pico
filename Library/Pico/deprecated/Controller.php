<?php
class Pico_Controller{
    private $_request;
    private $_view;

    public function __construct( $request ){
        $this->_request = $request;
        $this->init();

        try{
            call_user_func( array( $this, sprintf("%sAction", $request->action)));
        }
        catch( Exception $e ){
            die( sprintf('Action "%s" is not defined', $request->action) );
        }
    }

    protected function init(){
    }

    protected function redirect( $where, $how = 303 ){
        header( sprintf( 'Location: %s', $where, $how ));
        exit(1);
    }

    protected function forward( $action, $controller = null ){
        if( null == $controller ){
            $controller = $this;
        }
        call_user_func(array($controller, sprintf('%sAction', ucfirst($action))));
    }


    protected function getView(){
        return $this->_view;
    }

    protected function getRequest(){
        return $this->_request;
    }
}
