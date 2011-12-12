<?php
class Pico_Form_Content extends Nano_Form{
    public function __construct( $content, $item = null ){
        parent::__construct();

        $item = ( isset($item) ? $item : $content->item() );

        $target = urlencode(join( '/', array('/admin', $item->type, 'edit',  $item->id )));

        $this->addElements(array(
            'content[' . $content->id . '][value]' => array(
                    'wrapper'   => false,
                    'class'     => 'rich-text-editor',
                    'prefix'    => '<div class="item-content-wrapper">',
                    'type'      => 'textarea',
                    'value'     => $content->value
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
                    . '?target='
                    . $target
                    . '">delete</a></div></div>'
                )
        ));

        $this->setAttribute('action', '/admin/content/save/' . $content->id );
    }
}
