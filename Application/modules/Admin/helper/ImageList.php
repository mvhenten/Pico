<?php
class Helper_ImageList{
    function ImageList( $contents = null ){
        return $this->renderList( (array) $contents );
    }

    private function renderList( $contents ){
		$form = new Nano_Form();
		$form->setWrapper( new Nano_Element('div', array('id'=> 'image-thumbnails')));

		$fieldset = new Nano_Form_Element_Fieldset(array('class'=>'buttonbar'));

		$fieldset->addElement( 'select-all', array(
			'type'		=>'button',
			'wrapper'	=> false,
			'value'		=> 'select all',
			'onclick'	=> "$(this.form).select('.input-checkbox').each(function(el){el.up('dl').addClassName('active');el.checked=true});"
		));

		$fieldset->addElement( 'select-all', array(
			'type'=>'reset',
			'wrapper'	=> false,
			'value'=> 'clear selection',
			'onclick'	=> '$(this).up(\'form\').select(\'dl\').invoke(\'removeClassName\', \'active\');'
		));

		$fieldset->addElement( 'action', array(
			'type'		=> 'select',
			'wrapper'	=> false,
			'label'		=> 'With selected images do ',
			'options'	=> array(
				'delete'	=> 'delete images',
				'labels'	=> 'edit labels'
			)
		) );

		$form->addChild( $fieldset );

		$fieldset = new Nano_Form_Element_Fieldset(array('type'=>'div','id'=>'draggable-items'));

		foreach( $contents as $index => $item ){
			$img = new Nano_Element( 'img', array(
				'alt'	     => $item->name,
				'width'		 => 96,
				'height' 	 => 96,
				'ondblclick' => sprintf("document.location.href='/admin/image/edit/%d'", $item->id ),
				'onclick'	 => '$(this).up(\'dl\').toggleClassName(\'active\')',
				'src'		 => sprintf("/admin/image/view/thumbnail/%d", $item->id)
			));

			$fieldset->addElements(array(
				'title[' . $item->id . ']'	=> array(
					'type'	=> 'text',
					'value'	=> $item->name,
					'prefix'	=> '<dl class="list-item" id="image_' . $item->id . '">',
					'wrapper'	=> new Nano_Element('dt'),
					'validators' => array(
						array( 'StringLength', 0, 64),
					)
				),
				'selection[' . $item->id . ']' => array(
					'wrapper'	=> new Nano_Element('dd'),
					'type'	=> 'checkbox',
					'label'	=> (string) $img,
					'suffix'	=> '</dl>'
				)
			));
		}

		$form->addChild( $fieldset );

		//$form->addChild( new Nano_Element( 'script', null, "Sortable.create('image-thumbnails',{tag:'dl'});"));


		return $form;


        //$form = new Nano_Element( 'form' );
        //$table = new Nano_Element( 'table' );
        //
        //$tr = new Nano_Element( 'tr' );
        //$tr->addChild( new Nano_Element('th', null, 'Select') );
        //
        //foreach( array('title', 'description', 'visible') as $key ){
        //    $td = new Nano_Element( 'th' );
        //    $button = new Nano_Element( 'input', array(
        //        'type'  =>'submit',
        //        'value' => ucfirst( $key ),
        //        'name'  => 'order'
        //    ));
        //
        //    $td->addChild( $button );
        //    $tr->addChild( $td );
        //}
        //
        //$table->addChild( $tr );
        //
        //foreach( $contents as $item ){
        //    $tr = new Nano_Element( 'tr' );
        //    $select = new Nano_Element( 'input', array(
        //        'type'=>'checkbox',
        //        'name' => 'selection[' . $item->id . ']'
        //    ));
        //
        //    $tr->addChild( new Nano_Element( 'td', null, $select ) );
        //
        //    $link = sprintf( '<a href="/admin/%s/edit/%d">%s</a>', $this->getTypeName($item), $item->id, $item->name );
        //
        //    $tr->addChild( new Nano_Element('td', null, $link ) );
        //    $tr->addChild( new Nano_Element('td', null, $item->description ) );
        //    $tr->addChild( new Nano_Element('td', null, $item->visible ) );
        //
        //    $table->addChild( $tr );
        //
        //
        //}
        //
        //$form->addChild( $table );
        //
        //return $form;
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
