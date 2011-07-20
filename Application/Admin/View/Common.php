<?php
class Admin_View_Common extends Admin_View_Base{
    public function post( $request, $config ){
        $post = $request->getPost();

        if( $request->action == 'delete' && $post->confirm ){
            $item = new Model_Item($request->id);
            $type = $item->type;


            if( $post->confirm ){
                $item->delete();
            }

            //$action = $type == 'label' ? 'label' : 'list';
            //$this->response()->redirect('/admin/' . $type . '/' . $action );
            $view = $type == 'label' ? 'image/label' : $type;
            $this->response()->redirect( '/admin/' . $view );


        }
    }

    public function getDelete( $request, $config ){
        if( $request->id ){
            $item = new Model_Item($request->id);

            $this->template()->item = $item;
            return $this->template()->render( APPLICATION_ROOT . '/Admin/template/common/delete');
        }
    }
    public function getAdd( $request, $config ){
        $item = new Model_Item( array(
            'name'          => 'Untitled new ' . $request->id,
            'description'   => 'Description for untitled new ' . $request->id,
            'type'           => $request->id,
            'slug'          => 'untitled'
        ));

        $item->put();

        if( $item->type !== 'label' ){
            $content = new Model_ItemContent();
            $content->item_id = $item->id;
            $content->put();
        }

        $view = $item->type == 'label' ? 'image/label' : $item->type;

        $this->response()->redirect( '/admin/' . $view );
    }


}
