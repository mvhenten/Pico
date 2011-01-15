<?php
class Controller_Admin_Link extends Pico_AdminController{

    /**
     * utility function: Fetch all menu "groups".
     * menu system needs "groups" to operate.
     * Creates groups from default settings if none are found.
     *
     * @return Nano_Db_Query $query
     */
    private function getGroups(){
        $groups = Model_LinkGroup::get()->all();

        if( Null == $groups->current() ){
            $default  = (array) $this->getConfig()->settings->navigation['values'];

            foreach($default as $name => $value ){
                list($key, $value) = array_values($value);
                $group = new Model_LinkGroup( array('name' => $name, 'description' => $value ) );
                $group->put();
            }

            $groups = Model_LinkGroups::get()->all();
        }

        return $groups;

    }

    protected function saveAction(){
        $request = $this->getRequest();
        $model   = Model_Link::get( $request->post );
        $post    = $request->getPost();

        $form = new Form_EditLink( $model );


        $form->validate( $post );

        if( $form->isValid() ){
            $model->title = $post->title;
            $model->url   = $post->url;
            $model->group = $post->group;

            $model->put();

            $this->_redirect( $this->getView()->Url(array(
                'action'    => 'edit',
                'id'        => $model->id
            )) );
        }
        else{
            $this->_forward( 'add' );
            return;
        }

    }

    protected  function listAction(){
        //$form = new Form_EditLink( Model_Link::get() );
        $groups = $this->getGroups();

        $html = array();

        foreach( $groups as $group ){
            $html[] = $this->getView()->link( $group->description, array(
                'action'=>'list', 'id'=>$group->name ));
        }


        $this->getView()->content = $this->getView()->ul( $html );
        $this->getView()->actions = $this->getActions( Model_Link::get() );
    }

    protected function addAction(){
        $request = $this->getRequest();

        $form = new Form_EditLink( new Model_Link() );

        if( $request->isPost() ){
            $form->validate( $request->getPost() );
        }

        $this->getView()->content = $form;
        $this->getView()->actions = $this->getActions( Model_Link::get() );
    }

    protected function editAction(){
        $request = $this->getRequest();

        if( is_numeric( $request->id ) ){
            $item = Model_Link::get($request->id);
        }
        else{
            $item = Model_Link::get();

            $item->test = 1;


            var_dump( $item );
            return;
//            $item->group = 'main';
            //$item->group = $request->id;
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

        $this->getView()->headScript()->append(null, '$(function(){document.getElementById("main-content").scrollLeft = 10000})');
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

        $html[] = '<div class="float-left"><h2>' . join( ' &raquo; ',
            array_filter(array( $group->name, $item->title ))) . '</h2>';
        $html[] = '<span>&nbsp;&nbsp;</span>';
        $html[] = $this->getView()->Link( 'Add link', $url, array('class'=>'button'));
        $html[] = "</div>";

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
        $default  = (array) $this->getConfig()->settings->navigation['values'];
        $search   = Model_Setting::get()->all()->where('group', 'navigation');
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
