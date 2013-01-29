<?php
/**
 * Application/plugin/CustomField/Helper/Customfield.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


class CustomField_Helper_CustomField extends Nano_View_Helper {


    /**
     *
     *
     * @param unknown $item
     * @param unknown $field_name
     * @return unknown
     */
    function CustomField( $item, $field_name ) {
        $appendix = $item->appendix;

        if ( ! isset( $appendix->custom_fields ) ) {
            return null;
        }

        if ( ! isset( $appendix->custom_fields->$field_name ) ) {
            return null;
        }

        return htmlentities( $appendix->custom_fields->$field_name );
    }


}
