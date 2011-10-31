<?php
class Pico_View_Index extends Pico_View_Base{
    public function post( $request, $config ){
    }

    public function get( $request, $config ){
        $tpl_path = 'home';

        if( $request->secondary || ($request->primary != 'home' )){
            $id = ($request->secondary) ? $request->secondary : $request->primary;

            $item = $this->model('Item')->search(array(
                'where' => array('slug' => $id )
            ))->fetch();

        }
        else{
            $item = $this->template()->item = $this->model('Item')->search(array(
                'type'    => 'label',
                'limit'   => 1
            ))->fetch();
        }


        Nano_Log::error($item);
        $type = $item->type;
        $this->template()->item     = $item;
        $this->template()->labels   = $this->model('Item')->search(array('type' => 'label'))->fetchAll();

        if( method_exists( $this, "_get_$type" ) ){
            $method = "_get_$type";
            return $this->$method( $request, $config );
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
        $label = $this->model('Item')->search(array(
            'slug' => $request->primary ))->fetch();


        if( $label ){

            $pager = $label->pager('images', array(), array('page_size' => 1));

            $this->template()->pager  = $pager;
            $this->template()->image  = $pager->getPage($request->page)->fetch();
            $this->template()->label  = $label;

            $pager_range  = $pager->range(8);
            $thumb_page   = 1 + intval($pager_range[0]/8);
            
            $thumb_pager = $label->pager('images', array(), array('page_size' => 8 ));
            $this->template()->thumbnails = $thumb_pager->getPage($thumb_page);
            $this->template()->thumb_pager = $thumb_pager;
        }

        return $this->template()->render('image');
    }

}
