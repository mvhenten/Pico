<?php
class Form_EditItem extends Nano_Form{
    public function __construct( $item = null ){
        parent::__construct();

        $item = ( isset($item) ? $item : new Model_Item() );

        //$title = ( $item->id == null ) ? 'Create new' . $item->type : 'Editing ' . $item->name;

        $this->addElements(array(
            'toolbar' => array(
                'type' => 'fieldset',
                'class'   => 'toolbar',
                'elements' => array(
                    'save'  => array(
                        'wrapper' => false,
                        'type'  => 'submit',
                        'value' => 'Save changes',
                        //'disabled' => 'disabled'
                    ),
                    'delete' => ( null !== $item->id ? array(
                        'wrapper' => false,
                        'type'  => 'submit',
                        'value' => 'Delete ' . $item->name
                    ):null)
                )
            ),
            'viewport' => array(
                'type' => 'fieldset',
                'class' => 'item-form',
                'elements' => array(
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
                    'Save'  => array(
                        'type'  => 'submit',
                        'value' => 'Save changes'
                    )
                )
            ),
        ));
    }
}
