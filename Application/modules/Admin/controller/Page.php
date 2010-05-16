<?php
class Controller_Admin_Page extends Pico_AdminController{
    protected function indexAction(){
        $this->_forward('list');
    }

    protected  function listAction(){
        $items = new Model_Item( array('type'=>self::ITEM_TYPE_PAGE));

        $items = $items->search();

        $this->getView()->items = $items;

    }

    protected  function editAction(){
        $request = $this->getRequest();
        $page = new Model_Item();
        $page->type = self::ITEM_TYPE_PAGE;

        if( null !== $request->id ){
            $page->id = $request->id;
        }

        $form = $this->getItemForm( $page );

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

        $this->getView()->form = $form;

    }

    protected  function addAction(){
        $this->_forward( 'edit' );
    }

    protected function getMenu(){
        return array(
            'add'   => array(
                'target' => '/admin/page/add',
                'value'  => 'Add new page'
            )
        );
    }

}
