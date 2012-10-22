<?php
/**
 * Application/lib/View/Index.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Bison
 */


class Pico_View_Index extends Nano_App_View{

    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     */
    public function __construct( Nano_App_Request $request, $config ) {

        $this->template()->request      = $request;
        $this->template()->templatePath = $config['config']['settings']['template_path'];

        parent::__construct( $request, $config );
    }


    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     */
    public function post( Nano_App_Request $request, $config ) {
    }


    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     * @return unknown
     */
    public function get( Nano_App_Request $request, $config ) {
        list( $primary, $secondary ) = $request->pathParts(2);
        $slug = $primary ? $primary : 'home';

        if ( $secondary ) {
            $slug = $secondary;
        }

        $item = $this->model('Item')->search(array(
                'where' => array(
                    'slug' => $slug
                )
            ))->fetch();

        if ( $item && ($type = $item->type ) && method_exists($this, "_get_$type" ) ) {
            $method = "_get_$type";
            return $this->$method( $request, $config, $item );
        }


        return $this->template()->render('home');
    }


    /**
     *
     *
     * @param unknown $request
     * @param unknown $config
     * @param unknown $item
     * @return unknown
     */
    private function _get_page( $request, $config, $item ) {
        $this->template(array(
                'item'  => $item
            ));

        return $this->template()->render( $item->slug == 'home' ? 'home' : 'page'   );
    }


    /**
     *
     *
     * @param unknown $request
     * @param unknown $config
     * @param unknown $item
     * @return unknown
     */
    private function _get_label( $request, $config, $item ) {
        $pager = $item->pager('images', array(), array('page_size' => 12));
        $this->template(array(
                'pager' => $pager,
                'images' => $pager->getPage($request->page),
                'item'  => $item
            ));

        return $this->template()->render('label');
    }


    /**
     *
     *
     * @param unknown $request
     * @param unknown $config
     * @param unknown $item
     * @return unknown
     */
    private function _get_image( $request, $config, $item ) {
        list( $label_slug, $image_slug ) = $request->pathParts(2);
        
        if( $label_slug ){
            $this->template()->label = $this->model('Item')->search(array(
                    'where' => array(
                        'slug' => $label_slug
                    )
                ))->fetch();
        }

        $this->template()->image = $item;
        return $this->template()->render('image');
    }


}
