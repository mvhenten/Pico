<?php
/**
 * Application/plugin/Builder/lib/Page.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


class Builder_Page extends Builder_Abc {
    protected $_doctype = 'html';

    protected $_html;
    protected $_body;
    protected $_head;


    //$builder = new Builder_Page(array(
    //    'head'     => array(
    //      'children' => array(
    //            'type' => 'link',
    //            'attibutes' => array(
    //                'href' => 'style.css',
    //                'rel'  => 'stylesheet',
    //            )
    //        )
    //    ),
    //    'children' => array(
    //        array(
    //            'type' => 'block',
    //        )
    //    )
    //));


    /**
     *
     *
     * @return unknown
     */
    public function as_string() {
        $doctype = sprintf('<!DOCTYPE %s>', $this->doctype );

        return join( "\n", array( $doctype, $this->html ) );
    }



    /**
     *
     *
     * @return unknown
     */
    protected function _build__html() {
        $html = new Nano_Element( 'html' );
        $html->addChild( $this->head );
        $html->addChild( $this->body );

        return $html;
    }



    /**
     *
     *
     * @return unknown
     */
    protected function _build__head() {
        $head = new Nano_Element( 'head' );
        $head->setVertile();

        $this->_parse_arg( $this->arg('head'), $head );

        return $head;
    }



    /**
     *
     *
     * @return unknown
     */
    protected function _build__body() {
        $body = new Nano_Element( 'body' );
        $body->setVertile();

        $this->_parse_arg( $this->arg('body'), $body );

        return $body;
    }

    protected function _parse_arg( array $args=array(), Nano_Element $parent ){
        if ( !empty( $args ) && isset( $args['children'] ) ) {
            foreach ( $args['children'] as $child ) {
                $parent->addChild( $this->element( $child )) ;
            }
        }
    }


}
