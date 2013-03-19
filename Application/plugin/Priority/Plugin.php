<?php
/**
 * Application/plugin/ItemThumbnail/Plugin.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package default
 */


class Priority_Plugin extends Pico_View_Admin_Base {

    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     */
    public function post( Nano_App_Request $request, $config ) {
        //@list( , , , $id ) = $request->pathParts();

        $post   = (object) $request->post();

        foreach ( $post->priority as $item_id => $priority ) {
            $item = $this->model('Item', $item_id );
            $item->priority = $priority;

            $item->store(array( 'id' => $item->id ) );
        }
    }


    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     * @return unknown
     */
    public function get( Nano_App_Request $request, $config ) {
        $config = key_exists( 'priority', $config['config'] )
            ? $config['config']['priority'] : null;

        $items = $this->model('Item')->search(array(
                'where' => array('type' =>  $config['items'], 'visible' => 1 ),
                'order' => 'priority'
            ));

        $this->template()->items = $items;

        $this->template()->addTemplatePath( $this->_templatePath() );

        return $this->template()->render('list');
    }


    /**
     *
     *
     * @return unknown
     */
    private function _templatePath() {
        return  'Application/plugin/Priority/template';
    }


}
