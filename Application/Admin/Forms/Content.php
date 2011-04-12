<?php
class Form_Content extends Nano_Form{
    public function __construct( $content ){
        parent::__construct();

        //$item = ( isset($item) ? $item : new Model_Item() );
        $this->addElements(array(
            'content[' . $content->id . '][value]' => array(
                    'wrapper'   => false,
                    'class'     => 'rich-text-editor',
                    'prefix'    => '<div class="item-content-wrapper">',
                    'type'      => 'textarea',
                    'value'     => utf8_encode($content->value)
                ),
                'content[' . $content->id . '][save]'   => array(
                    'type'  => 'submit',
                    'value' => 'save',
                    'wrapper'   => false,
                    'prefix'    => '<div class="toolbar">'
                ),
                'content[' . $content->id . '][draft]'    => array(
                    'type'  => 'submit',
                    'value' => 'save draft',
                    'wrapper'   => false,
                    'suffix'    => '<a class="button" href="/admin/content/delete/'
                    . $content->id
                    . '">delete</a></div></div>'
                )
        ));

        $this->setAttribute('action', '/admin/content/save/' . $content->id );
    }
}
