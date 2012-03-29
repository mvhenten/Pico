<?php
class Builder_View extends Pico_View_Admin_Base {

    public function __construct( $request, $config ){
        parent::__construct( $request, $config );

        $this->template()->templatePath = 'Application';
    }


    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     */
    public function get( Nano_App_Request $request, $config ) {
        @list( , , , $action, $item_id ) = $request->pathParts;


        return $this->template()->render('plugin/Builder/template/main');
    }


    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     */
    public function post( Nano_App_Request $request, $config ) {
        @list( , , , $action, $item_id ) = $request->pathParts;

    }


}
