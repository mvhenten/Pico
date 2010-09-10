<?php
class Helper_LinkList extends Nano_View_Helper{
    function linkList( $contents = null ){
        return $this->renderList( (array) $contents );
    }

    private function renderList( $contents ){
        $ul = new Nano_Element('ul');
        foreach( $contents as $key => $value ){
            if( is_array( $value ) && isset( $value['target'] ) ){
                $value = array_merge( array('attributes' => null), $value );

                $content = $this->getView()->Link( $value['value'], $value['target'], $value['attributes'] );

                //$content = new Nano_Element( 'a', $attributes, $value['value'] );
            }
            else if( is_array( $value ) ){
                $content = $this->renderList( $contents );
            }

            $li = new Nano_Element( 'li', null, $content );

            $ul->addChild( $li );
        }

        return $ul;
    }

}
