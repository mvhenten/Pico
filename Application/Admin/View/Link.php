<?php
class Pico_View_Admin_Link extends Pico_View_Admin_Base{

    /**
     * utility function: Fetch all menu "groups".
     * menu system needs "groups" to operate.
     * Creates groups from default settings if none are found.
     *
     * @return Nano_Db_Query $query
     */
    private function getGroups(){
        $groups = Nano_Db_Query::get('LinkGroup');

        if( Null == $groups->current() ){
            $default  = (array) $this->getConfig()->settings->navigation['values'];

            foreach($default as $name => $value ){
                list($key, $value) = array_values($value);
                $group = new Model_LinkGroup( array('name' => $name, 'description' => $value ) );
                $group->put();
            }

            $groups = Nano_Db_Query::get('LinkGroup');
        }

        return $groups;

    }

    protected function saveAction(){
        $request = $this->getRequest();
        $model   = new Model_Link( $request->id );//Nano::Model( 'Link', $request->post );
        $post    = $request->getPost();

        $form = new Form_EditLink( $model );
        $form->validate( $post );

        if( $form->isValid() ){
            $model->title       = $post->title;
            $model->url         = $post->url;
            $model->group       = $post->group;
            $model->parent_id   = $post->parent_id;

            $model->put();


            $this->_redirect( $request->url(array(
                'action'    => 'edit',
                'id'        => $model->parent_id
            )) );
            return;
        }
        else{
            //echo "not valid";
            $this->_forward( 'add' );
            return;
        }
    }

    protected  function listAction(){
        $request = $this->getRequest();
        $groups = $this->getGroups();

        if( $request->id ){
            $groups->where( 'name', $request->id );

            //var_dump( $groups->current() );
            $items = Nano_Db_Query::get('Link')
                    ->where( 'group', $groups->current()->id )
                    ->order( 'parent_id')
                    ->order( 'priority' );

            $tree = $this->getView()->linkTree( $items, null );
            $this->getView()->content = $tree;//$form;

        }
        else{
            $html = array();

            foreach( $groups as $group ){
                $html[] = $this->getView()->link( $group->description, array(
                    'action'=>'list', 'id'=>$group->name ));
            }
            $this->getView()->content = $this->getView()->ul( $html );
        }

        $this->getView()->actions = $this->getActions( new Model_Link() );
    }

    protected function addAction(){
        $this->_forward( 'edit' );
    }

    protected function editAction(){
        $request = $this->getRequest();

        $model   = new Model_Link( $request->id );

        $form    = new Form_EditLink( $model );
        //return;

        if( $request->isPost() ){
            $form->validate( $request->getPost() );
        }

        if( $request->id ){
            $items = Nano_Db_Query::get('Link')
                    ->where( 'group', $model->group )
                    //->order( 'parent_id')
                    ->order( 'priority' );

            //echo $items[0]->title;
            //return;

            $html = $this->getView()->linkTree( $items, $model );
        }
        else{
            $html = $form;
        }

        $this->getView()->content = $html; // $tree;//$form;
        $this->getView()->actions = $this->getActions( $model );
        //return;
        $this->getView()->headScript()->append(null, '$(function(){document.getElementById("main-content").scrollLeft = 10000})');
    }

    protected function deleteAction(){
        $request = $this->getRequest();

        $item = new Model_Link($request->id);
        $group = $item->group;

        $children = Nano_Db_Query::get('Link')
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
        $request    = $this->getRequest();
        $parent_id  = '';

        if( $request->id ){
            $form = new Nano_Form(null ,array(
                'class'     =>  'float-right',
                'action'    =>  $request->url(array('action'=>'save','id'=>null))
            ));

            $parents = Nano_Db_Query::get('Link');
            $parents = array_combine( $parents->pluck('id'), $parents->pluck('title'));

            if( is_numeric( $request->id ) ){
                $parent_id = $item->id;
                $group     = new Model_LinkGroup( $item->group );
            }
            else{
                $group = Nano_Db_Query::get('LinkGroup')->where( 'name', $request->id )->current();
                $keys = array_keys( $parents );
                $parent_id = reset($keys);
            }

            $form->addElements(array(
                'title'     => array(
                    'type'  => 'hidden',
                    'value' => sprintf("New %s link %d", $group->description, count($parents) )
                ),
                'url'   => array(
                    'type' => 'hidden',
                    'value' => '/'
                ),
                'group'   => array(
                    'type' => 'hidden',
                    'value' => $group->id
                ),
                'parent_id' => array(
                    'wrapper' => false,
                    'type'  => 'select',
                    'label' => 'Select parent',
                    'options' => $parents,
                    'value'     => $parent_id
                ),
                'submit' => array(
                    'type'  => 'submit',
                    'value' => 'quick add',
                    'wrapper' => false
                )
            ));

            $html[] = $form;
        }
        else{
            $html = array(
                '<div class="float-left"><h2>Links&nbsp;&nbsp;</h2>',
                $this->getView()->Link( 'Add link', '/admin/link/add', array('class'=>'button')).
                '</div>'
            );
        }

        return join( "\n", $html);
    }

    protected function getMenu(){
        $default  = (array) $this->getConfig()->settings->navigation['values'];
        $search   = Nano_Db_Query::get('Link')->where('group', 'navigation');
        $items = array(); foreach( $items as $i ) $items[$i->name] = $i;

        $items = array_merge( $default, $items );
        $links = array();


        foreach( $items as $name => $item ){
            $item = (object) $item;
            $links[] = array(
                'target' => array('action' => 'list', 'id' => $name ),
                'value'  => $item->value
            );
        }

        return $this->getView()->linkList( $links );
    }

}
