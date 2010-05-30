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
        $actions = array_merge(array(
            'save'  => array(
                'wrapper' => false,
                'type'  => 'submit',
                'value' => 'Save changes?',
                'prefix' => sprintf('<h5>Editing <em>%s</em>&nbsp;</h5>', $item->name)
            ),
            'delete' => ( null !== $item->id ? array(
                'wrapper' => false,
                'type'  => 'submit',
                'value' => 'Delete ' . $item->name
            ):null)), $actions
        );

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
                    array('stringLength', array(0, 254), false )
                ),
            ),
            'visible'   => array(
                'type'  => 'checkbox',
                'value' => $item->visible,
                'label' => 'Visible'
            ),
        ), $extra );

        $elements = array_merge($elements, array(
            'Save'  => array(
                'type'  => 'submit',
                'value' => 'Save changes'
            )
        ));

        return new Nano_Form( 'item-' . $item->id, array(
            'elements' => array(
                'toolbar'   => array(
                    'type'      => 'fieldset',
                    'elements'  => $actions,
                    'class'     => 'toolbar'
                ),
                'viewport'  => array(
                    'type'      => 'fieldset',
                    'elements'  => $elements
                )
            )
        ));

        return $form;
    }
}
