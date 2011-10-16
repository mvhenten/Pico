<?php
class Pico_View_Index extends Pico_View_Base{
    public function post( $request, $config ){
    }

    public function get( $request, $config ){
        $tpl_path = 'home';


        if( $request->primary !== 'home' ){
            $item = $this->template()->item;
            $tpl_path = $item->type;
        }


        return $this->template()->render($tpl_path);
    }
}
