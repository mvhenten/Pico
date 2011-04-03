<?php
class Form_Item extends Nano_Form{
    public function __construct( $item = null ){
        parent::__construct();

        $item = ( isset($item) ? $item : new Model_Item() );

        $toolbar = array(
            'save'  => array(
                'prefix'    => '<div class="toolbar">',
                'wrapper'   => false,
                'type'      => 'submit',
                'value'     => 'Save changes'
            ),
            'add content'   => array(
              'suffix'  => null == $item->id ? '</div>' : '',
              'wrapper' => false,
              'type'    => 'submit',
              'value'   => 'add content item',
            ),
            'delete' => ( null !== $item->id ? array(
                'suffix'    => '</div>',
                'wrapper'   => false,
                'type'      => 'submit',
                'value'     => 'Delete ' . $item->name
            ):null)
        );

        $item_form = array(
            'type' => 'fieldset',
            'class' => 'item-form',
            'id'    => 'item-values',
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
                'slug' => array(
                    'type'          => 'text',
                    'value'         => $item->slug,
                    'label'         => 'Slug',
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
                'save-changes' => array(
                    'wrapper'   => new Nano_Element('div', array('class'=>'submit-wrapper')),
                    'type'      => 'submit',
                    'value'     => 'Save changes'
                )
            )
        );

        $this->addElement( 'item', $item_form );
    }
}
