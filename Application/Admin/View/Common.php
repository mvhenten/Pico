<?php
class Admin_View_Common extends Admin_View_Base{
    public function post( $request, $config ){
        $post = $request->getPost();

        if( $request->action == 'delete' && $post->confirm ){
            $item = $this->model('Item', $request->id );
            $type = $item->type;


            if( $post->confirm ){
                $item->delete();
            }

            $view = $type == 'label' ? 'image/label' : $type;
            $this->response()->redirect( '/admin/' . $view );
        }
    }

    public function getDelete( $request, $config ){
        if( $request->id ){
            $item = $this->model('Item', $request->id );

            $this->template()->item = $item;
            return $this->template()->render( APPLICATION_ROOT . '/Admin/template/common/delete');
        }
    }
    public function getAdd( $request, $config ){
        $item = $this->model('Item', array(
            'name'          => 'Untitled new ' . $request->id,
            'description'   => 'Description for untitled new ' . $request->id,
            'type'           => $request->id,
            'slug'          => 'untitled'
        ));

        $item->store();

        if( $item->type !== 'label' ){
            $c->model('ItemContent', array('item_id' => $item_id) )->store();
        }

        $view = $item->type == 'label' ? 'image/label' : $item->type;

        $this->response()->redirect( '/admin/' . $view );
    }


}
