<?php
/**
 * Application/lib/View/Admin/Content.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Bison
 */


class Pico_View_Admin_Content extends Pico_View_Admin_Base{

    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     */
    public function save( Nano_App_Request $request, $config ) {
        @list( , $controller, $action, $id ) = $request->pathParts();

        $post    = $request->post;

        foreach ( $post['content'] as $content_id => $value ) {
            $content = $this->model('ItemContent', $content_id );

            if ( isset($value['draft'] ) ) {
                $content->draft = $value['value'];
            }
            else {
                $content->value = $value['value'];
            }


            $content->store(array( 'id' => $content_id) );
        }

        if ( $request->target ) {
            $this->response()
            ->redirect( $request->target );
        }
    }


    /**
     *
     *
     * @param object  $request
     * @param unknown $config
     */
    public function delete( Nano_App_Request $request, $config ) {
        @list( , $controller, $action, $id ) = $request->pathParts();

        $this->model('ItemContent', $id )->delete();

        if ( $request->target ) {
            $this->response()
            ->redirect( $request->target );
        }
    }


    //public function post( $request, $config ){
    //    $post = $request->post();
    //
    //    $content = new Model_ItemContent( $request->id );
    //
    //    if( $request->action == 'save' ){
    //        $content->value = $post->content[$request->id]['value'];
    //    }
    //    else if( $request->action == 'draft' ){
    //        $content->draft = $post->content[$request->id]['draft'];
    //    }
    //    else if( $request->action == 'delete' ){
    //        if( $post->confirm ){
    //            $content->delete();
    //        }
    //    }
    //
    //    $content->put();
    //
    //    $item = new Model_Item( $content->item_id );
    //
    //    if( $item->type == 'label' ){
    //        $this->response()->redirect( '/admin/image/label/' . $item->id );
    //    }
    //    $this->response()->redirect( '/admin/' . $item->type . '/edit/' . $item->id );
    //}
    //
    //public function getAdd( $request, $config ){
    //    $content = new Model_ItemContent();
    //    $content->item_id = $request->id;
    //    $content->put();
    //
    //    $item = new Model_Item( $request->id );
    //
    //    if( $item->type == 'label' ){
    //        $this->response()->redirect( '/admin/image/label/' . $request->id );
    //    }
    //
    //    $this->response()->redirect( '/admin/' . $item->type . '/edit/' . $request->id );
    //}
    //
    //public function getDelete( $request, $config ){
    //    $content = new Model_ItemContent( $request->id );
    //    $item = new Model_Item( $content->item_id );
    //
    //
    //    $content->delete();
    //
    //    if( $item->type == 'label' ){
    //        $this->response()->redirect( '/admin/image/label/' . $item->id );
    //    }
    //
    //    $this->response()->redirect( '/admin/' . $item->type . '/edit/' . $item->id );
    //}

}
