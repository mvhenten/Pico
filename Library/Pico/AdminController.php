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

    protected function getItemForm( $item ){
    }

    public function deleteAction(){
        $request = $this->getRequest();
    
        $item = new Model_Item( array('id' => $request->id ) );
    
        $form = new Nano_Form();
    
        $form->addChild('h1', null, sprintf('You are about to delete "<em>%s<em>"', $item->name ));
        $form->addChild('p', null, sprintf('This cannot be undone, are you sure?', $item->name ));
    
        $form->addElements(array(
            'confirm' => array(
                'type'  => 'submit',
                'value' => 'Yes, delete ' . $item->name ,
                'wrapper' => false
            ),
            'cancel' => array(
                'type'  => 'submit',
                'value' => 'Cancel',
                'wrapper' => false
            ),
            'formid' => array(
                'type' => 'hidden',
                'value' => 'delete-confirm'
            )
        ));
    
        if( $request->isPost() && $post = $request->getPost() ){
            $types = array(
                self::ITEM_TYPE_IMAGE => 'image',
                self::ITEM_TYPE_PAGE => 'page',
                self::ITEM_TYPE_LABEL => 'label',
                self::ITEM_TYPE_NAV => 'navigation',
                self::ITEM_TYPE_CAT => 'category',
            );
    
            $type = $types[$item->type];
    
            if( $post->cancel != null ){
                $this->_redirect( sprintf('/admin/%s/edit/%d', $type, $item->id ) );
            }
            else{
                $item->delete();
                $this->_redirect( sprintf( '/admin/%s', $type ) );
            }
        }
    
        $this->getView()->actions = '<h2>Confirm deletion</h2>';
        $this->getView()->middle = $form;
    }
}
