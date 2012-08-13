<?php

class Navtree_Helper_Navigation extends Nano_View_Helper {

    private $_trees;

    function Navigation( $name, $parent=null  ) {
        if( $parent ){
            $tree = $this->_children( $name, $parent );
        }
        else{
            $tree = $this->_tree( $name );        
        }

        $items = $this->_plain_items( $tree );
        
        return $items;
    }
    
    private function _plain_items( $items ){
        $plain_items = array();
        
        foreach( $items as $item ){
            $plain_items[] = $item->item;
        }
        
        return $plain_items;
    
    }
    
    private function _children( $name, $path ){
        $map = $this->_url_x_item( $name );

        $parent = $map[$path];

        if( $parent ){
            $parent_tree = $this->_model_tree( $name )->children( $parent->item );
            return $parent_tree->children;
        }
        
        return array();
    }
    
    private function _url_x_item( $name ){
        $url_x_item = array();

        foreach( $this->_items( $name ) as $item ){
            $url_x_item[$item->item->url()] = $item;    
        }
        
        return $url_x_item;
    }
    
    
    private function _tree( $name ){
        return $this->_model_tree( $name )->tree();
    }
    
    private function _items( $name ){
        return $this->_model_tree( $name )->items();
    }

    private function _model_tree( $name ){
        if( ! isset( $this->_trees[$name] ) ){
            $this->_trees[$name] = new Navtree_Model_Tree( $name );
        }
        return $this->_trees[$name];
    }

}
