<?php
class Pico_View_Base extends Nano_View{
    public function __construct( $request, $config ){

        $this->template()->request = $request;
        $this->template()->templatePath = $config->settings->template_path;

        parent::__construct( $request, $config );
    }
}
