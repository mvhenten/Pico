<?php
class Helper_ItemForm{
    /**
     * Helper: creates a form for pico_item objects
     *
     * @param Model_Item $item Item to work with
     * @param array $extra Extra form fields to append as an addElements array
     * @param actions Extra actions to apply to form-toolbar
     */
    public function ItemForm( Model_Item $item, $extra = array(), $actions = array() ){
        $form = new Nano_Form(array('class'=>'reps'));
        $form->setWrapper( false );
        $table = new Nano_Element( 'table', array('class'=>'reps'));

        $actions = array_merge(array(
            'save'  => array(
                'wrapper' => false,
                'type'  => 'submit',
                'value' => 'Save changes',
                'label' => sprintf('Editing <em>%s</em>&nbsp; ', $item->name)
            ),
            'delete' => ( null !== $item->id ? array(
                'wrapper' => false,
                'type'  => 'submit',
                'value' => 'Delete ' . $item->name
            ):null)), $actions
        );


        $fieldset = new Nano_Form_Element_Fieldset( array(
            'type' => 'th',
            'class' => 'toolbar',
            'elements'  => $actions
        ));

        $row = new Nano_Element('tr');
        $row->addChild( $fieldset );
        $table->addChild( $row );

        $elements = array_merge(array(
            'type'  => array(
                'type'  => 'hidden',
                'value' => $item->type,
            ),
            'name' => array(
                'type'          => 'text',
                'value'         => $item->name,
                'label'         => 'Title',
                'validators'    => array(
                    array('stringLength', array(1, 64), false )
                ),
                'required'      => true
            ),
            'description'   => array(
                'type'  => 'textarea',
                'value' => $item->description,
                'label' => 'Description',
                'validators'    => array(
                    array('stringLength', array(1, 254), false )
                ),
            ),
            'visible'   => array(
                'type'  => 'checkbox',
                'value' => $item->visible,
                'label' => 'Visible'
            ),
        ), $extra );


        $fieldset = new Nano_Form_Element_Fieldset(array(
            'type'  => 'div',
            'class' => 'abps',
            'elements'=> $elements
        ));

        $fieldset->addElement( 'save', array(
            'type'  => 'submit',
            'value' => 'Save changes'
        ));

        $table->addChildren(array(
            'tr' => array( 'children' => array('td' => array(
                'class'    => 'viewport',
                'children' => array('div' => array(
                    'content' => $fieldset,
                    'class'   => 'reps'
                )
            ))))
        ));


        $form->addChild( $table );
        return $form;
    }
}
