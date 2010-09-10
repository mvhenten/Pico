<?php
class Controller_Admin_Link extends Pico_AdminController{
    protected function indexAction(){
        $this->_forward('list');
    }

    protected  function listAction(){
        $request = $this->getRequest();

        $html = array();
        $link = Model_Link::get();
        $link->group = $request->id;

        if( $request->id ){
            $item = Model_LinkGroup::get($request->id);
            $html[] = '<h4>' . $item->name . '</h4>';
            $html[] = '<p>' . $item->description . '</p>';
            $form = new Form_EditLink( $link );
            $html[] = $form;
            $this->getView()->left = join( "\n", $html );

            $items = Model_Link::get()->all()
                    ->where( 'group', $link->group )
                    ->order( 'parent_id')
                    ->order( 'priority' );

            $tree = $this->getView()->linkTree( $items, $link );

            $this->getView()->middle = $tree;

        }
    }

    protected function addAction(){
        $request = $this->getRequest();

        $post = $request->getPost();
        $item = Model_Link::get();

        $item->title = $post->title;
        $item->priority = 0;
        $item->parent_id = (int) $post->parent_id;
        $item->group = $request->id;
        //$item->description = $post->description;

        $id = $item->put();

        $url = $this->getView()->url( array( 'action' => 'edit', 'id' => $id ) );

        $this->_redirect( $url );
    }

    protected function editAction(){
        $request = $this->getRequest();


        $item = Model_Link::get( $request->id );

        if( $request->isPost() ){
            $post = $request->getPost();

            if( $post->delete ){
                $id = (int) $item->parent_id;
                $item->delete();

                $url = $this->getView()->url( array(
                    'action' => $id > 0 ? 'edit' : 'list',
                    'id'    => $id > 0 ? $id : $item->group
                ));

                if( $id > 0 ){
                    $items = Model_Link::get()->all()->where('parent_id', $id );

                    foreach( $items as $item ){
                        $item->parent_id = 0;
                        $item->put();
                    }
                }

                $this->_redirect( $url );

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

        $this->getView()->middle = $tree;

        $group = Model_LinkGroup::get($item->group);
        $link = Model_Link::get();
        $link->parent_id = $item->id;
        $link->group     = $item->id;

        $html[] = '<h4>' . $group->name . '</h4>';
        $html[] = '<p>' . $group->description . '</p>';
        $form = new Form_EditLink( $link );
        $html[] = $form;
        $this->getView()->left = join( "\n", $html );

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
