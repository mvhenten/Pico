<?php
class Helper_Pager extends Nano_View_Helper{
    public function Pager( $items ){
        $request = $this->getView()->getRequest();
        $count  = $items->count();

        if( $request->offset ){
            $items->offset( $request->offset );
        }

        $limit  = $items->getLimit();
        $offset = $items->getOffset();


        $pager = new Nano_Form_Element_Fieldset('pager', array(
            'class' => 'pager'
        ));


        if( $offset > 0 && ($pos = ($limit+$offset) ) ){
            $pager->addChild( $this->getView()->link(
                sprintf( '&laquo; previous %d', $limit), array(
                'action'    => 'list',
                'id'        => $request->id . ($offset-$limit > 0 ? "?offset=" . ($offset-$limit) : '')
            )));
        }

        if( ($pos = ($limit+$offset)) && $pos <= $count ){
            $next = min( $pos+$limit, $pos + abs( $count - $pos ));

            $pager->addChild( $this->getView()->link(
                sprintf("next %d-%d of %d &raquo;", $limit+$offset+1, $next, $count), array(
                    'action'    => 'list',
                    'id'        => $request->id . "?offset=" . ($limit + $offset)
            )));
        }

        return $pager;
    }
}
