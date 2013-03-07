<?php
/**
 * Application/lib/Form/Item.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


class Pico_Form_Item extends Nano_Form{

    /**
     * Basic item form
     *
     * @param unknown $item (optional)
     */
    public function __construct( $item = null ) {
        parent::__construct( null, array( 'class' => 'form content-form autosave' ) );

        $item = ( isset($item) ? $item : new Model_Item() );

        //$toolbar = array(
        //    'save'  => array(
        //        'prefix'    => '<div class="toolbar">',
        //        'wrapper'   => false,
        //        'type'      => 'submit',
        //        'value'     => 'Save changes'
        //    ),
        //    'add content'   => array(
        //        'suffix'  => null == $item->id ? '</div>' : '',
        //        'wrapper' => false,
        //        'type'    => 'submit',
        //        'value'   => 'add content item',
        //    ),
        //    'delete' => ( null !== $item->id ? array(
        //            'suffix'    => '</div>',
        //            'wrapper'   => false,
        //            'type'      => 'submit',
        //            'value'     => 'Delete ' . $item->name
        //        ):null)
        //);

        $item_form = array(
            'type' => 'fieldset',
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
                        array('stringLength', array(1, 64) )
                    ),
                    'required'      => true
                ),
                'slug' => array(
                    'type'          => 'text',
                    'value'         => $item->slug,
                    'label'         => 'Slug',
                    'validators'    => array(
                        array('stringLength', array(1, 64) )
                    ),
                    'required'      => true
                ),
                'description'   => array(
                    'type'  => 'textarea',
                    'value' => $item->description,
                    'label' => 'Description',
                    'validators'    => array(
                        array('stringLength', array(0, 254) )
                    ),
                ),
                'priority' => array(
                    'type'  => 'number',
                    'value' => $item->priority,
                    'label' => 'priority',
                    'validators' => array(
                        array('is_numeric', array(), array('priority must be a number')),
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
