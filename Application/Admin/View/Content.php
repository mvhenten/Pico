<?php
class Admin_View_Content extends Nano_View{
    public function post( $request, $config ){
        $post = $request->getPost();

        $content = new Model_ItemContent( $request->id );

        if( $request->action == 'save' ){
            $content->value = $post->content[$request->id]['value'];
        }
        else if( $request->action == 'draft' ){
            $content->draft = $post->content[$request->id]['draft'];
        }
        else if( $request->action == 'delete' ){
            if( $post->confirm ){
                $content->delete();
            }
        }

        $content->put();

        $item = new Model_Item( $content->item_id );

        if( $item->type == 'label' ){
            $this->response()->redirect( '/admin/image/label/' . $item->id );
        }
        $this->response()->redirect( '/admin/' . $item->type . '/edit/' . $item->id );
    }

    public function getAdd( $request, $config ){
        $content = new Model_ItemContent();
        $content->item_id = $request->id;
        $content->put();

        $item = new Model_Item( $request->id );

        if( $item->type == 'label' ){
            $this->response()->redirect( '/admin/image/label/' . $request->id );
        }

        $this->response()->redirect( '/admin/' . $item->type . '/edit/' . $request->id );
    }

    public function getDelete( $request, $config ){
        $content = new Model_ItemContent( $request->id );
        $item = new Model_Item( $content->item_id );


        $content->delete();

        if( $item->type == 'label' ){
            $this->response()->redirect( '/admin/image/label/' . $item->id );
        }

        $this->response()->redirect( '/admin/' . $item->type . '/edit/' . $item->id );
    }

}
