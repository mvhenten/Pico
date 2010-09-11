<?php
/**
 * @class Helper_LinkList
 * @package Admin/Helper
 *
 *  @method string Returns an unordered list of links
 */
class Helper_LinkList extends Nano_View_Helper{
    function linkList( $contents = null ){
        $ul = new Nano_Element('ul');
        foreach( $contents as $key => $value ){
            if( is_array( $value ) && isset( $value['target'] ) ){
                $value = array_merge( array('attributes' => null), $value );
                $content = $this->getView()->Link( $value['value'], $value['target'], $value['attributes'] );
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
