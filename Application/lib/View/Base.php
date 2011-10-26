<?php
class Pico_View_Base extends Nano_View{
    public function __construct( $request, $config ){

        $this->template()->request = $request;
        $this->template()->templatePath = $config->settings->template_path;

        Nano_Log::error($request);

        if( $request->secondary || ($request->primary != 'home' )){
            $id = ($request->secondary) ? $request->secondary : $request->primary;

            $item = $this->model('Item')->search(array(
                'where' => array('slug' => $id )
            ))->fetch();

            $this->template()->item = $item;
        }

        parent::__construct( $request, $config );
    }
}
