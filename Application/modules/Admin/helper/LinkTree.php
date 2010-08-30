<?php
class Helper_LinkTree extends Nano_View_Helper{
    function LinkTree( $items, $current ){
        $sorted = $this->sortItems( $items, $current );
        $container = new Nano_Element( 'div', array( 'class' => 'pico-linktree'));

        $ul     = new Nano_Element( 'ul');

        foreach( $sorted as $parent ){
            $ul->addChild( $this->renderItem( $parent, (int) $current->id ));
        }


        $container->addChild( $ul );
        return $container;
    }

    private function renderItem( $item, $current ){
        $html = array();

        $url = $this->getView()->url( array( 'id' => $item->id ) );

        $li = new Nano_Element('li', array('class' => ( $item->active ? 'active':'')));
        $li->addChild( new Nano_Element( 'a', array('href' => $url ), $item->title ) );

        if( $item->active && count( $item->children ) ){
            $container = new Nano_Element( 'div', array('class' => 'itemGroup') );
            $ul = new Nano_Element( 'ul' );

            foreach( $item->children as $child ){
                $el = $this->renderItem( $child, $current );
                $ul->addChild( $el );
            }
            $container->addChild( $ul );
            $li->addChild( $container );
        }
        if( $item->id == $current ){
            $form = new Form_EditLink( Model_Link::get( $current ) );
            $container = new Nano_Element( 'div', array('class' => 'itemForm'), $form );
            if( count($item->children) ){
                $el->addChild( $container );
            }
            else{
                $li->addChild($container);
            }
        }
        return $li;
    }

    private function sortItems( $items, $item ){
        $sort = array();
        foreach( $items as $obj ) $sort[$obj->id] = (object) $obj->properties();

        $sorted = array();
        foreach( $sort as $id => $obj ){
            $id = (int) $id;
            $obj->parent_id = (int) $obj->parent_id;
            $obj->active = $id == $item->id ? true : false;
            $obj->children = array();

            if( $obj->parent_id > 0 ){
                $sort[$obj->parent_id]->children[$id] = $obj;
                if( $obj->active ){
                    // walk up to activate parents
                    while( $obj->parent_id > 0 ){
                        $obj = $sort[$obj->parent_id];
                        $obj->active = true;
                    }

                }
            }
            else{
                $sorted[$id] = $obj;
            }

        }

        return $sorted;
    }
}
