<?php
class Admin_View_Page extends Admin_View_Base{
    public function post( $request, $config ){
        $post = $request->getPost();

        $page = new Model_Item( $request->id );

        if( stripos( $page->slug,'untitled' ) === 0 ){
            $page->slug = $request->slug($post->name);
        }
        else{
            $page->slug = $request->slug($post->slug);
        }

        $page->name = $post->name;
        $page->description = $post->description;
        $page->visible = (bool) $post->visible;

        $page->put();
        $this->response()->redirect( '/admin/page/edit/' . $page->id );
    }

    public function getList( $request, $config ){
        $pages = Nano_Db_Query::get( 'Item', array('type'=>'page'));
        $this->template()->pages = $pages;
        return $this->template()->render( APPLICATION_ROOT . '/Admin/template/page/list');
    }


    protected  function getEdit(  $request, $config ){
        $template = $this->template();

        $page = new Model_Item( $request->id );

        $form = new Form_Item( $page );

        $template->page = $page;
        $template->form = $form;
        $this->template()->pages = Nano_Db_Query::get( 'Item', array('type'=>'page'));;

        return $this->template()->render( APPLICATION_ROOT . '/Admin/template/page/edit');
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
