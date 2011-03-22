<?php
class Admin_View_Page extends Nano_View{
    public function post( $request, $config ){
        $post = $request->getPost();

        $page =new Model_Item( array(
            'id'            => $request->id,
            'name'          => $post->name,
            'description'   => $post->description,
            'visible'       => (bool) $post->visible
        ));

        $page->put();
        $this->response()->redirect( '/admin/page/edit/' . $page->id );
    }

    public function getList( $request, $config ){
        $pages = Nano_Db_Query::get( 'Item', array('type'=>'page'));
        $this->template()->pages = $pages;
        return $this->template()->render( 'Admin/template/page/list');
    }


    protected  function getEdit(  $request, $config ){
        $template = $this->template();

        $page = new Model_Item( $request->id );


        $form = new Form_Item( $page );

        $template->page = $page;
        $template->form = $form;
        $this->template()->pages = Nano_Db_Query::get( 'Item', array('type'=>'page'));;

        return $this->template()->render( 'Admin/template/page/edit');
    }

    public function getContent( $request, $config ){
        $content = new Model_ItemContent();
        $content->item_id = $request->id;
        $content->put();

        $item = new Model_Item( $request->id );

        if( $item->type == 'label' ){
            $this->response()->redirect( '/admin/image/label/' . $request->id );
        }

        $this->response()->redirect( '/admin/image/edit/' . $request->id );
    }


}
