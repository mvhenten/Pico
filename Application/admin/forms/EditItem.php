<?php
class Form_EditItem extends Nano_Form{
    public function __construct( $item = null ){
        parent::__construct();

        $item = ( isset($item) ? $item : new Model_Item() );


        $content = array();
        foreach( $item->getContent() as $value ){
            $content += array(
                'content[' . $value->id . '][value]' => array(
                    'wrapper'   => false,
                    'class'     => 'rich-text-editor',
                    'prefix'    => '<div class="item-content-wrapper">',
                    'type'      => 'textarea',
                    'value'     => $value->value
                ),
                'content[' . $value->id . '][delete]'   => array(
                    'type'  => 'submit',
                    'value' => 'delete',
                    'wrapper'   => false,
                    'prefix'    => '<div class="toolbar">'
                ),
                'content[' . $value->id . '][draft]'    => array(
                    'type'  => 'submit',
                    'value' => 'save draft',
                    'wrapper'   => false,
                    'suffix'    => '</div></div>'
                )
            );
        }

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
                    'wrapper'   => false,
                    'type'      => 'submit',
                    'value'     => 'Save changes'
                )
            )
        );

//        $this->addElements( $toolbar );
        $this->addElement( 'item', $item_form );

        if( count($content) ){
            $this->addElement( 'content-column', array(
                'type'  => 'fieldset',
                'tagname'   => 'div',
                'elements' => $content
            ));
        }

        //$this->addElements( $toolbar );

    }
}
