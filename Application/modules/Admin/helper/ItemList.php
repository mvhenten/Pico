<?php
class Helper_ItemList extends Nano_View_Helper{
    public function ItemList( $items ){
        $request = $this->getView()->getRequest();
        
        $form = new Nano_Form( 'list_items_' . $request->getRouter()->controller );
        
        $viewport = new Nano_Form_Element_Fieldset( 'viewport', array(
            'prefix'    => '<div class="formElementWrapper">',
            'suffix'    => '</div>',
            'tagname' => 'table',
            'class'   => 'viewport'
        ));

        $head = new Nano_Element( 'tr', null
            , '<th>name</th><th>description</th>'
            . '<th>visible</th><th colspan=3>updated</th>'
        );
        
        $viewport->addChild( $head );
        $class = 'even';

        foreach( $items as $item ){
            $class = ($class == 'even') ? 'uneven' : 'even';
            $row = new Nano_Element( 'tr', array(
                'class'      => $class,
                'onclick'    => '$(this).toggleClassName("active")',
                'ondblclick' => 'document.location.href = "/admin/page/edit/' . $item->id . '"'
            ));
            
            $id = 'item-' . $item->id;
            $updated = (null == $item->updated) ? $item->inserted : $item->updated;

            $input = new Nano_Form_Element_Input( 'item[' . $item->id . ']', array(
                'type'  => 'checkbox',
                'label' => $item->name,
                'id'    => 'item-' . $item->id,
                'wrapper' => new Nano_Element('td')
            ));

            $row->addChild( $input );

            $row->addChild('td', null, sprintf('<label for="%s">%s</label>', $id, $item->description) );
            $row->addChild('td', null, sprintf('<label for="%s">%s</label>', $id, ($item->visible>0?'yes':'no') ) );
            $row->addChild('td', null, sprintf('<label for="%s">%s</label>', $id, $updated) );
            
            //$url = new Helper_Url('test');
            
            $row->addChild('td', array('width'=>'10%'),
                $this->getView()->Link('edit', array(
                    'action' => 'edit', 'id' => $item->id
            )));

            $row->addChild('td', array('width'=>'10%'),
                $this->getView()->Link('delete', array(
                    'action' => 'delete', 'id' => $item->id
            )));

            $viewport->addChild( $row );
        }
        
        $form->addElements(array(
            'toolbar'   => array(
                'type'  => 'fieldset',
                'class' => 'toolbar',
                'elements' => array(
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
                        'name'      => 'action',
                        'type'		=> 'select',
                        'wrapper'	=> false,
                        'label'		=> 'With selected items do ',
                        'onchange'  => 'this.form.submit()',
                        'options'	=> array(
                            'delete'	=> 'delete',
//                            'labels'	=> 'edit labels'
                        ),
                    )
                )
            )
        ));
        
        $form->addChild( $viewport );

        return $form;
    }

}
