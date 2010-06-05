<?php
class Form_EditLabels extends Nano_Form{
    public function __construct( $labels, $selected ){
        parent::__construct();

        foreach( $labels as $label ){
            $elements['selection[' . $label->id . ']'] = array(
                'type'  => 'checkbox',
                'label' => $label->name,
                'value' => in_array( $label->id, $selected )
            );
        }
        $this->setAttribute( 'id', 'labels-bulk' );

        $this->addElements(array(
            'toolbar'   => array(
                'type'  => 'fieldset',
                'class' => 'toolbar',
                'legend' => '<h5>Editing labels</h5>',
                'elements' => array(
                    'reset' => array(
                        'wrapper' => false,
                        'type' => 'button',
                        'value' => 'clear all',
                        'onclick' => '$$(".input-checkbox").each(function(el){el.checked=false;});'
                    ),
                    'select-all' => array(
                        'wrapper' => false,
                        'type' => 'button',
                        'value' => 'select all',
                        'onclick' => '$$(".input-checkbox").each(function(el){el.checked=true;});'
                    ),
                    'save' => array(
                        'wrapper' => false,
                        'type' => 'submit',
                        'value' => 'Save changes'
                    ),
                )
            ),
            'viewport' => array(
                'type'  => 'fieldset',
                'class' => 'viewport',
                'elements' => $elements
            ),
            'save' => array(
                'wrapper' => false,
                'type' => 'submit',
                'value' => 'Save changes'
            ),
        ));
    }
}
