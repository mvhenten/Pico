<?php
class Pico_AdminController extends Nano_Controller{
    const ITEM_TYPE_IMAGE = 1;
    const ITEM_TYPE_PAGE  = 2;
    const ITEM_TYPE_LABEL = 3;
    const ITEM_TYPE_NAV   = 4;
    const ITEM_TYPE_CAT   = 5;

    public function preDispatch(){
        $this->getView()->disableViewScript();

        if( ! Nano_Session::session()->auth ){
            $this->_redirect( '/admin/user/login' );
        }
    }

    public function postDispatch(){
        $this->getView()->submenu = $this->getMenu();
    }

    protected function getMenu(){
        return array();
    }

    //protected function getItemForm( $item ){
    //}

    protected function deleteAction(){
        $request = $this->getRequest();
        $form    = new Nano_Form();
        $klass   = sprintf( 'Model_%s', ucfirst($request->getRouter()->controller));
        
        if( is_numeric( $request->id ) ){
            $ids = array($request->id);
        }
        else{
            $ids = array_map( 'intval', explode(',', $request->id ));
        }
        
        if( $request->isPost() && $post = $request->getPost() ){
            if( $post->confirm ){
                foreach( $ids as $id ){
                    $klass::get($id)->delete();
                }
            }
            $this->_redirect( $this->getView()->Url(array(
                'action'=> 'list',
                'id'    => null
            )));
        }
    
        $list = new Nano_Element('ul');
        
        foreach( $ids as $id ){
            $item = $klass::get($id, $klass);
            $list->addChild( 'li', null, $item->name );
        }
        
        $form->addChild( 'p', null, sprintf(
            'You are about to delete the following %s'
            , count($ids) > 1 ? 'items' : 'item'
        ));
        $form->addChild( $list );
        $form->addChild( 'p', null, 'This cannot be undone, are you sure?');
        
    
        $form->addElements(array(
            'confirm' => array(
                'prefix'    => '<p>',
                'type'  => 'submit',
                'value' => 'Confirm',
                'wrapper' => false
            ),
            'cancel' => array(
                'type'  => 'submit',
                'value' => 'Cancel',
                'wrapper' => false,
                'suffix'    => '</p>'
            )
        ));
        
        $this->getView()->actions = '<h2>Confirm deletion</h2>';
        $this->getView()->content = $form;
    }
}
