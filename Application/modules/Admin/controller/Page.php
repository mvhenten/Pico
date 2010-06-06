<?php
class Controller_Admin_Page extends Pico_AdminController{
    protected function indexAction(){
        $this->_forward('list');
    }

    protected  function listAction(){
        $items = new Model_Item( array('type'=>self::ITEM_TYPE_PAGE));
        $items = $items->search();

        $form = new Form_ListItems( $items );

        $this->getView()->mainLeft = $form;
    }

    protected  function editAction(){
        $request = $this->getRequest();
        $page = new Model_Item(array('id'=>$request->id));
        $page->type = self::ITEM_TYPE_PAGE;

        $form = new Form_EditItem( $page );

        if( $request->isPost() ){
            $post = $request->getPost();
            $form->validate( $post );

            if( ! $form->hasErrors() ){
                $page->name = $post->name;
                $page->description = $post->description;
                $page->visible     = (bool) $post->visible;

                $page->save();
                $this->_redirect( '/admin/page/edit/' . $page->id );
            }

        }

        $this->getView()->mainLeft = $form;//new Form_EditItem();
    }

    protected  function addAction(){
        $this->_forward( 'edit' );
    }

    protected function getMenu(){
        return array(
            'list'  => array(
                'target' => '/admin/page/list',
                'value'  => 'Show pages'
            ),
            'add'   => array(
                'target' => '/admin/page/add',
                'value'  => 'Add new page'
            )
        );
    }

}
