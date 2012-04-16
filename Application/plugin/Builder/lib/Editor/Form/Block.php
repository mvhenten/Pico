<?php
class Builder_Editor_Form_Block extends Nano_Form{
    public function __construct() {
        parent::__construct();

        $form = $this->_get_fieldset(
                array(
                    'name'  => 'block-editor',
                    'label' => 'CSS block',
                    'elements' => $this->_get_css_block_editor()
                )
            );


        $this->addElement( 'css-block', $form );
    }

    private function _get_css_block_editor (){
        $collect = array();

        $spec = array(
            'width-height' => array('Width and height', array('width','height') ),
            'padding' => array('Padding', array('top', 'right', 'left','bottom' ),'padding' ),
            'margin' => array('Margin', array('top', 'right', 'left','bottom' ), 'margin' ),
        );

        foreach( $spec as $name => $element_spec ){
            @list( $title, $elements, $prefix ) = $element_spec;

            $collect[$name] = $this->_get_fieldset(
                array(
                    'name'  => $name,
                    'label' => $title,
                    'elements' => $this->_build_css_block_elements( $elements, $prefix )
                )
            );
        }

        return $collect;
    }

    private function _get_fieldset( array $part ){
        $part = (object) $part;

        $css_class = isset($part->class) ? $part->class : 'element-editor';

        return array(
            'type' => 'fieldset',
            'class' => $css_class,
            'id'    => 'editor-' . $part->name,
            'label' => $part->label,
            'elements' =>
                $part->elements

        );
    }

    private function _build_css_block_elements ( array $elements, $prefix=null ) {
        $collect = array();

        foreach( $elements as $name ){
            $name = join( '-', array_filter( array($prefix, $name) ) );
            $collect["$name"] = $this->_build_css_block_element( "$name" );
        }

        return $collect;
    }

    private function _build_css_block_element ( $name ){
        return array(
            'type' => 'text',
            'value' => '',
            'label' => $name,
            'validators' => array(
                array( 'preg_match',
                      array('/^\d+(\.\d+)?(em|px|pt|\%)$/'),
                      array("$name must be a valid css value")
                )
            )
        );
    }



}
