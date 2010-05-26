<?php
class Helper_ImageList{
    function ImageList( $contents = null ){
        return $this->renderList( (array) $contents );
    }

    private function renderList( $contents ){
		$form = new Nano_Form(array('class'=>'reps'));
        $form->setWrapper( false );

		$fieldset = new Nano_Form_Element_Fieldset(array(
            'type'  => 'td',
            'class'=>'buttonbar',
            'elements'  => array(
                'select-all'    => array(
                    'type'		=>'button',
                    'wrapper'	=> false,
                    'value'		=> 'select all',
                    'onclick'	=> "$(this.form).select('.input-checkbox').each(function(el){el.up('dl').addClassName('active');el.checked=true});"
            	),
                'reset' => array(
                    'type'=>'reset',
                    'wrapper'	=> false,
                    'value'=> 'clear selection',
                    'onclick'	=> '$(this).up(\'form\').select(\'dl\').invoke(\'removeClassName\', \'active\');'
        		),
                'action' => array(
                    'type'		=> 'select',
                    'wrapper'	=> false,
                    'label'		=> 'With selected images do ',
                    'onchange'  => 'this.form.submit()',
                    'options'	=> array(
                        'delete'	=> 'delete images',
                        'labels'	=> 'edit labels'
                    ),
                )
            )
        ));

        $table = new Nano_Element( 'table', array('class'=>'reps') );
        $row = new Nano_Element( 'tr' );
        $row->addChild( $fieldset );
        $table->addChild( $row );


		$fieldset = new Nano_Form_Element_Fieldset(array(
            'type'  =>'div',
            'class' => 'abps',
            'id'    => 'images',
        ));

		foreach( $contents as $index => $item ){
			$img = new Nano_Element( 'img', array(
                'class'     => 'thumbnail',
				'alt'	     => $item->name,
				'width'		 => 96,
				'height' 	 => 96,
				'src'		 => sprintf("/admin/image/view/thumbnail/%d", $item->id),
                'onclick'    => '$(this).up(\'dl\').toggleClassName(\'active\');',
                'ondblclick' => 'document.location.href = $(this).up(\'dl\').down(\'a\').href;return false;'
			));

            $link = new Nano_Element( 'a', array(
                'href'  => '/admin/image/edit/' . $item->id,
                'title' => 'Edit ' . $item->name,
                'style'  => 'display: none;'
            ), 'Edit ' . $item->name );
            //
            //$link->addChild( $img );

            $wrapper =  new Nano_Element('dt');
            $wrapper->addChild( $link );


			$fieldset->addElements(array(
				'title[' . $item->id . ']'	=> array(
					'type'	=> 'text',
					'value'	=> $item->name,
					'prefix'	=> '<dl class="list-item" id="image_' . $item->id . '">',
					'wrapper'	=> $wrapper,
                    //'label'     => $link,
					'validators' => array(
						array( 'StringLength', 0, 64),
					)
				),
				'selection[' . $item->id . ']' => array(
					'wrapper'	=> new Nano_Element('dd'),
					'type'	=> 'checkbox',
					'label'	=> (string) $img,
					'suffix'	=> '</dl>',
				)
			));
		}

        $row = new Nano_Element( 'tr' );
        $cell = new Nano_Element( 'td', array('height'=>'100%'));
        $wrapper = new Nano_Element( 'div', array( 'class' => 'reps'));

        $table->addChild( $row->addChild( $cell->addChild( $wrapper->addChild( $fieldset ) ) ) );
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
