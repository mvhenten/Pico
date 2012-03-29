<?php
/**
 * Application/plugin/Builder/lib/Abc.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


abstract class Builder_Abc {
    private $_constructor_args;



    /**
     *
     *
     * @param unknown $args
     */
    public function __construct( array $args = array() ) {
        $this->_constructor_args = $args; //func_get_args();
    }



    /**
     *
     *
     * @return unknown
     */
    public function __toString() {
        return $this->as_string();
    }


    /**
     *
     *
     * @param unknown $name
     * @return unknown
     */
    public function __get( $name ) {
        if ( ($property = "_$name")
            && (isset($this->$property) || property_exists( $this, $property ))) {
            if ( ! isset( $this->$property )
                && method_exists( $this, "_build_$property" ) ) {
                $method = "_build_$property";
                $this->$property = $this->$method();
            }
            return $this->$property;
        }
    }



    /**
     *
     *
     * @param unknown $name
     * @return unknown
     */
    public function arg( $name ) {
        if ( isset( $this->_constructor_args[$name] ) ) {
            return $this->_constructor_args[$name];
        }

        return array();
    }



    /**
     *
     *
     * @param unknown $args
     * @return unknown
     */
    public function element( $args ) {
        $args = array_merge( array( 'type' => 'div', 'attributes' => array() ), $args );

        if( $args['type'] == 'block' ){
            return new Builder_Block( $args );
        }

        return new Nano_Element( $args['type'], $args['attributes']);
    }


}
