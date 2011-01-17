<?php
//@todo deprecate this and remove.
class Pico_AdminController extends Nano_Controller{
    const ITEM_TYPE_IMAGE = 1;
    const ITEM_TYPE_PAGE  = 2;
    const ITEM_TYPE_LABEL = 3;
    const ITEM_TYPE_NAV   = 4;
    const ITEM_TYPE_CAT   = 5;

    public function preDispatch(){
        //$this->getView()->disableViewScript();

        if( ! Nano_Session::session()->auth ){
            $this->_redirect( '/admin/user/login' );
        }
    }

    public function postDispatch(){
        $this->template()->submenu = $this->getMenu();
    }

    protected function getMenu(){
        return array();
    }

    //protected  function addAction(){
    //    $this->_forward( 'edit' );
    //}
    //
    //protected  function editAction(){
    //    $request    = $this->getRequest();
    //    $klass      = sprintf( 'Model_%s', ucfirst($request->controller) );
    //
    //    $item = $klass::get( $request->id );
    //
    //    if( $request->id == null ){
    //        // set up defaults for new items
    //        $count = $item->all()->count();
    //        $item->name     = sprintf( 'New %s %d', $request->controller, $count);
    //        $item->visible  = true;
    //    }
    //
    //    $form = new Form_EditItem( $item );
    //
    //    if( $request->isPost() && $post = $request->getPost() ){
    //        $form->validate( $post );
    //
    //        if( $form->isValid() ){
    //            $item->name        = $post->name;
    //            $item->description = $post->description;
    //            $item->visible     = !is_null($post->visible);
    //            $id = $item->put();
    //
    //            if( $post->add_content ){
    //                $content = Model_ItemContent::get();
    //                $content->item_id = (null == $item->id ? $id : $item->id);
    //                $content->put();
    //            }
    //
    //            if( null !== $post->content ){
    //                foreach( $post->content as $key => $value ){
    //                    $content = Model_ItemContent::get($key);
    //                    if( isset($value['delete']) ){
    //                        $content->delete();
    //                    }
    //                    else{
    //                        $content->value = $value['value'];
    //                        $content->put();
    //                    }
    //                }
    //            }
    //
    //
    //            $this->_redirect( $this->getView()->Url(array(
    //                'action'    => 'edit',
    //                'id'        => (null == $item->id ? $id :$item->id )
    //            )));
    //
    //        }
    //        else{
    //            $this->getView()->errors = $form->getErrors();
    //        }
    //    }
    //
    //    $this->getView()->headScript()->append('/js/light-rte/jquery.rte.js');
    //    $this->getView()->headScript()->append(null, '$(function(){$(\'.rich-text-editor\').rte()})' );
    //    $this->getView()->Style()->append( '/js/light-rte/rte.css');
    //
    //    $this->getView()->content = $form;
    //    $this->getView()->actions = new Nano_Element('h2', null, $item->id ?
    //                    sprintf('Editing <em>%s</em>', $item->name ) : sprintf('New %s', $request->controller ));
    //
    //    $this->getView()->actions .= $this->getView()->Link('add ' . $request->controller
    //                            , array('action' => 'add', 'id'=>null)
    //                            , array('class'=>'button'));
    //
    //    $this->getView()->actions .= $this->getView()->Link(sprintf('list %ss', $request->controller)
    //                            , array('action' => 'list', 'id'=>null)
    //                            , array('class'=>'button'));
    //}
    //
    //
    //protected function deleteAction(){
    //    $request = $this->getRequest();
    //    $form    = new Nano_Form();
    //    $klass   = sprintf( 'Model_%s', ucfirst($request->getRouter()->controller));
    //
    //    if( is_numeric( $request->id ) ){
    //        $ids = array($request->id);
    //    }
    //    else{
    //        $ids = array_map( 'intval', explode(',', $request->id ));
    //    }
    //
    //    if( $request->isPost() && $post = $request->getPost() ){
    //        if( $post->confirm ){
    //            foreach( $ids as $id ){
    //                $klass::get($id)->delete();
    //            }
    //        }
    //        $this->_redirect( $this->getView()->Url(array(
    //            'action'=> 'list',
    //            'id'    => null
    //        )));
    //    }
    //
    //    $list = new Nano_Element('ul');
    //
    //    foreach( $ids as $id ){
    //        $item = $klass::get($id, $klass);
    //        $list->addChild( 'li', null, $item->name );
    //    }
    //
    //    $form->addChild( 'p', null, sprintf(
    //        'You are about to delete the following %s'
    //        , count($ids) > 1 ? 'items' : 'item'
    //    ));
    //    $form->addChild( $list );
    //    $form->addChild( 'p', null, 'This cannot be undone, are you sure?');
    //
    //
    //    $form->addElements(array(
    //        'confirm' => array(
    //            'prefix'    => '<p>',
    //            'type'  => 'submit',
    //            'value' => 'Confirm',
    //            'wrapper' => false
    //        ),
    //        'cancel' => array(
    //            'type'  => 'submit',
    //            'value' => 'Cancel',
    //            'wrapper' => false,
    //            'suffix'    => '</p>'
    //        )
    //    ));
    //
    //    $this->getView()->actions = '<h2>Confirm deletion</h2>';
    //    $this->getView()->content = $form;
    //}
}
