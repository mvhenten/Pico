<?php
class Controller_Admin_Link extends Pico_AdminController{
    protected function indexAction(){
        $this->_forward('list');
    }

    protected  function listAction(){
        $request = $this->getRequest();
        
        if( $request->id ){
            $this->_forward('edit');
        }
        else{
            $items = Model_LinkGroup::get()->all()->limit(1);
            $this->_redirect( $this->getView()->Url( array(
                'action' => 'list',
                'id'     => $items[0]->id
            )));
        }
        
    }

    protected function addAction(){
        $request = $this->getRequest();
        $group   = Model_LinkGroup::get( $request->id );

        $item = Model_Link::get();
        
        $item->title     = sprintf("New %s link", $group->name );
        $item->parent_id = 0;
        $item->priority  = 0;
        $item->group     = $request->id;
        $item->url       = '/';
        
        $form = new Form_EditLink( $item );
        
        if( $request->isPost() ){
            $post = $request->getPost();
            
             $form->validate( $post );
             
             $errors = $form->getErrors();
             
            
            if( empty( $errors ) ){
                $values = array_intersect_key( (array) $post, array(
                    'title'     => 1,
                    'parent_id' => 1,
                    'priority'  => 1,
                    'group'     => 1
                ));
                
                foreach( $values as $key => $value ){
                    $item->$key = $value;
                }
                
                $id = $item->put();
                
                
                $this->_redirect( $this->getView()->url( array(
                    'action' => 'edit',
                    'id'     => $id
                )));
            }
            else{
                print_r( $errors );
            }
        }
        
        $this->getView()->content = $form;
        $this->getView()->actions = $this->getActions( $item );
    }

    protected function editAction(){
        $request = $this->getRequest();
        
        if( $request->action == 'list' ){
            $item = Model_Link::get();
            $item->group = $request->id;
        }
        else{
            $item = Model_Link::get($request->id);
        }

        if( $request->isPost() ){
            $post = $request->getPost();

            if( $post->delete ){
                $this->_redirect( $this->getView()->url(array(
                    'action'    => 'delete',
                    'id'        => $request->id
                )));
            }

            $item->title = $post->title;
            $item->priority = $post->priority;
            $item->parent_id = $post->parent_id;
            $item->description = $post->description;

            $item->put();
        }

        $items = Model_Link::get()->all()
                ->where( 'group', $item->group )
                ->order( 'parent_id')
                ->order( 'priority' );

        $tree = $this->getView()->linkTree( $items, $item );

        $this->getView()->content = $tree;
        $this->getView()->actions = $this->getActions( $item );
        
        $this->getView()->content .= '<script>$(\'main-content\').scrollLeft'
                                  . ' = $(\'main-content\').getWidth();</script>';
    }
    
    protected function deleteAction(){
        $request = $this->getRequest();
        
        $item = Model_Link::get($request->id);
        $group = $item->group;
        
        $children = Model_Link::get()->all()
            ->where( 'parent_id', $request->id );
            
        foreach( $children as $child ){
            $child->parent_id = $item->parent_id;
            $child->put();
        }
        
        $item->delete();
        
        $this->_redirect( $this->getView()->Url(array(
            'action'    => 'list',
            'id'        => $group
        )) );
    }
    
    protected function getActions( $item ){
        $group  = Model_LinkGroup::get($item->group);
        $url    = $this->getView()->Url(array('action' => 'add', 'id' => $group->id ));

        $search = ModeL_Link::get()->all()->where('group', $group->id );
        $parents = array(); foreach( $search as $i => $p ) $parents[$p->id] = $p->title;
        
        $html[] = '<h2>' . join( ' &raquo; ',
            array_filter(array( $group->name, $item->title ))) . '</h2>';
        $html[] = '<span>&nbsp;&nbsp;</span>';
        $html[] = $this->getView()->Link( 'Add link', $url, array('class'=>'button'));
        
        $form = new Nano_Form(null ,array(
            'class'     =>  'float-right',
            'action'    =>  $url
        ));
        
        $form->addElements(array(
            'title'     => array(
                'type'  => 'hidden',
                'value' => sprintf("New %s link %d", $group->name, count($parents) )
            ),
            'url'   => array(
                'type' => 'hidden',
                'value' => '/'
            ),
            'parent_id' => array(
                'wrapper' => false,
                'type'  => 'select',
                'label' => 'Select parent',
                'options' => $parents,
                'value'     => ($item->parent_id > 0 ? $item->parent_id : null )
            ),
            'submit' => array(
                'type'  => 'submit',
                'value' => 'quck add',
                'wrapper' => false
            )
        ));
        
        $html[] = $form;
        return join( "\n", $html);        
    }

    protected function getMenu(){
        $items = Model_LinkGroup::get()->all();

        $links = array();
        foreach( $items as $item ){
            $links[] = array(
                'target'=> array( 'action' => 'list', 'id' => $item->id),
                'value'  => $item->name,
            );
        }

        return $this->getView()->linkList( $links );
    }

}
