<?php
/**
 * Application/plugin/ItemThumbnail/Plugin.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package default
 */


class CustomField_Plugin extends Pico_View_Admin_Base {

    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     */
    public function post( Nano_App_Request $request, $config ) {
        @list( , , , $action, $item_id ) = $request->pathParts;
        
        $item = $this->model( 'Item', $item_id );
        
        $item->appendix->custom_fields = $request->post['custom_field'];        
        $item->store(array('id' => $item_id ));

        $url  = $this->_itemUrl( $request, $item );
        $this->response()->redirect($url);
    }

    private function _itemUrl( $request, $item ) {
        $url = $request->url;
        $url->pathParts( array('admin', $item->type, 'edit', $item->id ) );
        return $url;
    }

}
