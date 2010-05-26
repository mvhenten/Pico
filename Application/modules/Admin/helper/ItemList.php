<?php
class Helper_ItemList{
    function ItemList( $items, $config = array()){
        $config = (object) array_merge(array(
            'key'   => 'id',
            'columns' => array(
                'Title' => 'title',
                'Updated' => 'updated'
            ),
            'sortable' => array('title','updated'),
        ), $config);

        $table = new Nano_Element( 'table' );
        $head = new Nano_Element( 'tr' );

        foreach( $config->columns as $title => $key ){
            $head->addChild( 'th',null, $title );
        }

        $table->addChild( $head );


        foreach( $items as $key => $item ){
            $row = new Nano_Element('tr');

            foreach( $config->columns as $title => $key ){
                $row->addChild( 'td', null, $item[$key] );
            }

            $table->addChild( $row );
        }

        return $table;



    }

    private function renderList( $contents ){
        $form = new Nano_Element( 'form' );
        $table = new Nano_Element( 'table' );

        $tr = new Nano_Element( 'tr' );
        $tr->addChild( new Nano_Element('th', null, 'Select') );

        foreach( array('title', 'description', 'visible') as $key ){
            $td = new Nano_Element( 'th' );
            $button = new Nano_Element( 'input', array(
                'type'  =>'submit',
                'value' => ucfirst( $key ),
                'name'  => 'order'
            ));

            $td->addChild( $button );
            $tr->addChild( $td );
        }

        $table->addChild( $tr );

        foreach( $contents as $item ){
            $tr = new Nano_Element( 'tr' );
            $select = new Nano_Element( 'input', array(
                'type'=>'checkbox',
                'name' => 'selection[' . $item->id . ']'
            ));

            $tr->addChild( new Nano_Element( 'td', null, $select ) );

			$link = sprintf( '<a href="/admin/%s/edit/%d">%s</a>', $this->getTypeName($item), $item->id, $item->name );

            $tr->addChild( new Nano_Element('td', null, $link ) );
            $tr->addChild( new Nano_Element('td', null, $item->description ) );
            $tr->addChild( new Nano_Element('td', null, $item->visible ) );

            $table->addChild( $tr );


        }

        $form->addChild( $table );

        return $form;
    }

    private function getTypeName( $item ){
        $types = array(
            Pico_AdminController::ITEM_TYPE_IMAGE   => 'image',
            Pico_AdminController::ITEM_TYPE_PAGE    => 'page',
            Pico_AdminController::ITEM_TYPE_CAT     => 'category',
            Pico_AdminController::ITEM_TYPE_NAV     => 'navigation',
            Pico_AdminController::ITEM_TYPE_LABEL   => 'label'
        );

        return $types[$item->type];
    }

}
