<?php
/**
 * @class Helper_LinkList
 * @package Admin/Helper
 *
 *  @method string Returns an unordered list of links
 */
class Helper_Ul extends Nano_View_Helper{
    function ul( $contents = null ){
        $ul = new Nano_Element( 'ul' );

        foreach( $contents as $value ){
            $ul->addChild( new Nano_Element( 'li', null, $value ) );
        }

        return $ul;
    }
}
