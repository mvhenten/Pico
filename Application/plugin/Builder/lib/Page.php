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

        $args = $this->arg('head');

        if ( isset( $args['children'] ) ) {
            foreach ( $args['children'] as $child ) {
                $head->addChild( $this->element( $child )) ;
            }
        }

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

        return $body;
    }


}
