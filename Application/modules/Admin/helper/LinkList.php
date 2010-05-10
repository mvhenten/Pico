<?php
class Helper_LinkList{
    function linkList( $contents = null ){
        return $this->renderList( (array) $contents );
    }

    private function renderList( $contents ){
        $ul = new Nano_Element('ul');
        foreach( $contents as $key => $value ){
            if( is_array( $value ) && isset( $value['target'] ) ){
                $attributes = isset($value['attributes'])?$value['attributes']:array();
                $attributes['href'] = $value['target'];

                $content = new Nano_Element( 'a', $attributes, $value['value'] );
            }
            else if( is_array( $value ) ){
                $content = $this->renderList( $contents );
            }

            $li = new Nano_Element( 'li', array('class'=>$key), $content );

            $ul->addChild( $li );
        }

        return $ul;
    }

}
