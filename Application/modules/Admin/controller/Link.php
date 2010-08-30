<?php
class Controller_Admin_Link extends Pico_AdminController{
    protected function indexAction(){
        $this->_forward('list');
    }

    protected  function listAction(){
        $request = $this->getRequest();
        $links   = array();

        if( $request->item ){
            $item = Model_Link::get( $request->item );
            $items = Model_Link::get()->all()
                    ->where( 'group', $item->group );
        }
        else if( $request->id ){
            $items = Model_Link::get()->all()
                    ->where( 'group', $request->id )
                    ->where( 'parent_id', 0 );

            foreach( $items as $item ){
                $href = $this->getView()->url(array('action'=>'edit', 'id'=>$item->id));
                $links[] = $this->getView()->link( $href,$item->title );
            }

            $this->getView()->mainLeft = join('\n', $links );
        }
    }

    protected function editAction(){
        $request = $this->getRequest();


        $item = Model_Link::get( $request->id );

        if( $request->isPost() ){
            $post = $request->getPost();

            $item->title = $post->title;
            $item->priority = $post->priority;
            $item->parent_id = $post->parent_id;
            $item->description = $post->description;
            //$item->parent_id = $post
            //foreach( $request->getPost() as $key => $value ){
            //    $item->$key = $value;
            //}

            $item->put();

            //var_dump( $post ); exit();

            //$this->_redirect( $this->getView()->url() );
        }

        $items = Model_Link::get()->all()
                ->where( 'group', $item->group )
                ->order( 'parent_id')
                ->order( 'priority' );

        $tree = $this->getView()->linkTree( $items, $item );

        //$tree->addChild( new Nano_Element(  '<b>hier</b>';

        $this->getView()->middle = $tree;
        //$this->getView()->right  = new Form_EditLink( $item );


    }
}
