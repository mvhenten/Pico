<?php
class Helper_ImageList extends Nano_View_Helper{
    public function ImageList( $items, $actions = array('delete' => 'delete items') ){
        $request = $this->getView()->getRequest();
        $pager = $this->getView()->Pager( $items ); // copy paste for now

        $form = new Nano_Form( 'list_items_' . $request->getRouter()->controller );

        $viewport = new Nano_Form_Element_Fieldset( 'image-list', array(
            'tagname' => 'ul',
        ));

        foreach( $items as $item ){
            $wrapper = new Nano_Form_Element_Fieldset( 'list-item-' . $item->id, array(
                'tagname' => 'li',
                'class'    => 'image-list-item'
            ));

            $checkbox = new Nano_Form_Element_Input( 'item[' . $item->id . ']', array(
                'type'  => 'checkbox',
                'label' => $item->name,
                'id'    => 'item-' . $item->id,
                'wrapper' => $wrapper
            ));

            //$wrapper->addChild( $checkbox );

            $img = sprintf('<img src="/image/thumbnail/%d" class="thumbnail" />', $item->id);

            $link = $this->getView()->Link($img,
                array(
                    'action'=>'edit',
                    'id'=>$item->id
                ),
                array(
                    'onclick' => 'e = $(\'item-' . $item->id . '\'); e.checked = !e.checked; return false;',
                    'ondblclick' => 'document.location.href = this.href'
                )
            );

            $wrapper->addChild($link);

            if( $item->label_id != null ){
                $wrapper->addChild( new Nano_Form_Element_Input('priority[' . $item->id . ']', array(
                    'class' => 'item-priority',
                    //'label' => 'priority',
                    'value' => (int) $item->priority,
                    'size'  => '1',
                    'wrapper' => false
                )));
            }

            $viewport->addChild( $checkbox );
        }

        $toolbar = new Nano_Form_Element_Fieldset('toolbar', array(
            'class' => 'toolbar',
            'elements' => array(
                'select-all'    => array(
                    'type'		=>'button',
                    'wrapper'	=> false,
                    'value'		=> 'select all',
                    'onclick'	=> '$(\'.input-checkbox\').attr(\'checked\', true)'
                ),
                'reset' => array(
                    'type'=>'reset',
                    'wrapper'	=> false,
                    'value'=> 'clear selection',
                    'onclick'	=> '$(\'.input-checkbox\').attr(\'checked\', false)'
                ),
                'action' => array(
                    'name'      => 'action',
                    'type'		=> 'select',
                    'wrapper'	=> false,
                    'label'		=> 'With selected items do ',
                    'onchange'  => 'this.form.submit()',
                    'options'	=> array(
                        'delete'	=> 'delete',
                        'labels'	=> 'edit labels'
                    ),
                ),
                //'go' => array(
                //    'type' => 'submit',
                //    'value' => 'go'
                //)
            )
        ));

        $form->addChild( $toolbar );
        $toolbar->addChild( $pager );
        $form->addChild( $viewport );
        //$form->addElements(array(
        //));

        $toolbar = new Nano_Form_Element_Fieldset('toolbar-bottom', array(
            'class' => 'toolbar',
            'elements' => array(
                'save' => array(
                    //'wrapper' => new Nano_Element('div', array('class'=>'toolbar')),
                    'wrapper' => false,
                    'type' => 'submit',
                    'value' => 'save'
               )
            )
        ));

        $pager = $this->getView()->Pager( $items ); // copy paste for now
        $toolbar->addChild( $pager );
        $form->addChild( $toolbar );


        return $form;
    }

}
