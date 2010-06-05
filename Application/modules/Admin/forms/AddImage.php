<?php
class Form_AddImage extends Nano_Form{
    public function __construct(){
        parent::__construct();

        $this->addElements(array(
            'toolbar' => array(
                'type' => 'fieldset',
                'legend' => 'Upload a new image',
                'class'   => 'toolbar'
            ),
            'viewport' => array(
                'type' => 'fieldset',
                'class' => 'viewport',
                'elements' => array(
                    'image' => array(
                        'type'  => 'file',
                    ),
                    'upload'    => array(
                        'type'  => 'submit',
                        'value' => 'Submit'
                    ),
                    'results' => array(
                        'type'    => 'fieldset',
                        'legend'  => 'uploaded images',
                        'id'      => 'upload-results'
                    )
                )
            ),
        ));

        //$this->setAttribute( 'target', 'go');
        $this->setAttribute( 'action', '/admin/image/add');
        $this->setAttribute( 'class', 'uploadForm' );

        $this->setAttribute( 'id', 'image-add');


    }
}
