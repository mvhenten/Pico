<?php
class Controller_Admin_Page extends Pico_AdminController{
    protected function indexAction(){
        $this->_forward('list');
    }

    protected  function listAction(){
        $request = $this->getRequest();
        
        if( $request->isPost() && $post = $request->getPost() ){
            if( $post->action == 'delete' ){
                $ids = array_keys( $post->item );
                
                $this->_redirect( $this->getView()->url(array(
                    'action'    => 'delete',
                    'id'        => join(",", $ids )
                )));
            }
        }
        
        
        $items = Model_Page::get()->all();
        $count = Model_Page::get()->all()->count();
        
        if( $count == 0 ){
            $this->_redirect( $this->getView()->Url(array(
                'action' => 'add',
                'id'     => null
            )));     
        }

        $this->getView()->content = $this->getView()->ItemList( $items );            
        $this->getView()->actions = new Nano_Element('h2', null,'Pages');
        $this->getView()->actions .= $this->getView()->Link('Add page'
                                , array('action' => 'add')
                                , array('class'=>'button'));
    }
    
    protected  function addAction(){
        $this->_forward( 'edit' );
    }

    protected  function editAction(){
        $request = $this->getRequest();
        
        $item = Model_Page::get( $request->id );
        
        if( $request->id == null ){
            // set up defaults for new items
            $count = $item->all()->count();
            $item->name     = 'New Page ' . $count;
            $item->visible  = true;
        }
        
        $form = new Form_EditItem( $item );
        
        if( $request->isPost() && $post = $request->getPost() ){
            $form->validate( $post );
            
            if( $form->isValid() ){
                $item->name        = $post->name;
                $item->description = $post->descriptoin;
                $item->visible     = !is_null($post->visible);
                $id = $item->put();
                
                if( $post->add_content ){
                    $content = Model_ItemContent::get();
                    $content->item_id = (null == $item->id ? $id : $item->id);
                    $content->put();
                }
                
                foreach( $post->content as $key => $value ){
                    $content = Model_ItemContent::get($key);
                    if( isset($value['delete']) ){
                        $content->delete();
                    }
                    else{
                        $content->value = $value['value'];                    
                        $content->put();                        
                    }
                }
                
                $this->_redirect( $this->getView()->Url(array(
                    'action'    => 'edit',
                    'id'        => (null == $item->id ? $id :$item->id )
                )));
                
            }
            else{
                $this->getView()->errors = $form->getErrors();
            }
        }
        
        $this->getView()->headScript()->append('/js/light-rte/jquery.rte.js');
        $this->getView()->headScript()->append(null, '$(function(){$(\'.rich-text-editor\').rte()})' );
        $this->getView()->Style()->append( '/js/light-rte/rte.css');
        
        $this->getView()->content = $form;
        $this->getView()->actions = new Nano_Element('h2', null, $item->id ?
                        sprintf('Editing <em>%s</em>', $item->name ) : 'Add new page');
        
        $this->getView()->actions .= $this->getView()->Link('add page'
                                , array('action' => 'add', 'id'=>null)
                                , array('class'=>'button'));
        
        $this->getView()->actions .= $this->getView()->Link('list pages'
                                , array('action' => 'list', 'id'=>null)
                                , array('class'=>'button'));
    }
}
