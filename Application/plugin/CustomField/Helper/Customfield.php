<?php
class CustomField_Helper_CustomField extends Nano_View_Helper {

    function CustomField( $item, $field_name ) {
        $appendix = $item->appendix;

        if( ! isset( $appendix->custom_fields ) ){
            return '';
        }
        
        if( ! isset( $appendix->custom_fields->$field_name ) ){
            return '';
        }
        
        return htmlentities( $appendix->custom_fields->$field_name );
    }
}
