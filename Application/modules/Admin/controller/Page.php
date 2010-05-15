<?php
class Controller_Admin_Page extends Pico_AdminController{
    protected function indexAction(){
        $this->_forward('list');
    }

    protected  function listAction(){

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

            foreach( $form->getChildren() as $child ){
                $name = $child->getAttribute('name');

                if( $name == 'submit' ){
                    continue;
                }
                else if( $name == 'visible' && null !== $post->$name){
                    $page->$name = 1;
                }
                else{
                    $page->$name = $post->$name;
                }
            }

            $page->save();

            $this->_redirect( '/admin/page/edit/' . $page->id );

            $e = $form->getChildren();
            exit;

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
