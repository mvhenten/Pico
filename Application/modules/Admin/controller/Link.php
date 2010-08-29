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
        $items = Model_Link::get()->all()
                ->where( 'group', $item->group )
                ->order( 'parent_id');

        $this->getView()->mainLeft = $this->getView()->linkTree( $items, $item );
        $this->getView()->mainRight = 'boo';


    }
}
