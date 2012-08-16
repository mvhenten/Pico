<?php
class Wufoo_Plugin extends Pico_View_Admin_Base {

    public function post( Nano_App_Request $request, $config ) {
        @list( , , $action, $item_id ) = $request->pathParts;

        $item       = $this->model( 'Item', $item_id );
        $post       = (object) $request->post;

        if( isset( $post->delete ) ){
           $item->appendix()->wufoo_form = null;
        }
        else{
           $embed_code = $post->embed_code;
           $item->appendix()->wufoo_form = $embed_code; 
        }    
        
        $item->store(array('id' => $item_id  ));
        $url  = $this->_itemUrl( $request, $item );
        $this->response()->redirect($url);
    }

    private function _itemUrl( $request, $item ) {
        $url = $request->url;
        $url->pathParts( array('admin', $item->type, 'edit', $item->id ) );
        return $url;
    }

}
