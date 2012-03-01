<?php
class Pico_Helper_Pager extends Nano_View_Helper{


    /**
     *
     *
     * @param unknown $type (optional)
     * @return unknown
     */
    function Pager( $name, array $model_args = array(), $pager_args = array() ) {
        return $this->model( $name )->pager('search', $model_args, $pager_args );
    }

    function model ( $name ){
        foreach ( Nano_Autoloader::getNamespaces() as $ns => $val ) {
            $class_name = sprintf('%s_Model_%s', ucfirst($ns), ucfirst($name));
            if ( class_exists( $class_name )) {
                return new $class_name( $arguments );
            }

            $class_name = sprintf('%s_Schema_%s', ucfirst($ns), ucfirst($name));
            if ( class_exists( $class_name )) {
                return new $class_name( $arguments );
            }
        }
        throw new Exception( "Unable to resolve $name" );
    }
}
