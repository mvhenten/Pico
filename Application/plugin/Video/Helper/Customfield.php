<?php
class Video_Helper_Video extends Nano_View_Helper {

    function Video( $item, $field_name ) {
        $appendix = $item->appendix;

        if( ! isset( $appendix->custom_fields ) ){
            return null;
        }
        
        if( ! isset( $appendix->custom_fields->$field_name ) ){
            return null;
        }
        
        return htmlentities( $appendix->custom_fields->$field_name );
    }
}
