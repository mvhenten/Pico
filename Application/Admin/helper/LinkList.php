<?php
/**
 * @class Helper_LinkList
 * @package Admin/Helper
 *
 *  @method string Returns an unordered list of links
 */
class Helper_LinkList extends Nano_View_Helper{
    function linkList( $contents = null ){
        $links  = array();
        $active = null;
        $ul = new Nano_Element('ul');
        
        foreach( $contents as $key => $value ){
            $value = array_merge( array('attributes' => null), $value );
            $a = $this->getView()->Link( $value['value'], $value['target'], $value['attributes'] );

            $li = new Nano_Element( 'li' );
            $li->addChild( $a );
            $ul->addChild( $li );
            
            $klass = $a->getAttribute('class');
           
            if( strpos( 'active', $klass ) > -1 ){
                // only one active entry per list, always the last one
                if( $active ){
                    $klass = $active->getAttribute('class');
                    $klass = str_replace('active', '', $klass );
                    $active->setAttribute( 'class', $klass );
                }
                
                $active = $a;
            } 
        }
        
        return $ul;
    }
}
