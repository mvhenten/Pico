<?php
class Pico_View_Index extends Pico_View_Base{
    public function post( $request, $config ){
    }

    public function get( $request, $config ){
        $tpl_path = 'home';


        if( $request->primary !== 'home' ){
            $item = $this->template()->item;
            $type = $item->type;

            if( method_exists( $this, "_get_$type" ) ){
                $method = "_get_$type";
                return $this->$method( $request, $config );
            }
        }

        return $this->template()->render($tpl_path);
    }

    private function _get_label( $request, $config ){
        $item = $this->template()->item;

        $tpl_path = $item->type;

        $pager = $item->pager('images', array(), array('page_size' => 12));

        $this->template()->pager  = $pager;
        $this->template()->images = $pager->getPage($request->page);
        $this->template()->label  = $item;

        return $this->template()->render('label');
    }

    private function _get_image( $request, $config ){
        $image = $this->template()->item;

        $this->template()->image  = $image;

        return $this->template()->render('image');
    }

}
