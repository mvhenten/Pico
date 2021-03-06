<?php
/**
 * Application/lib/Model/Item.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package default
 */


class Pico_Model_Item extends Pico_Schema_Item {

    /**
     *
     *
     * @param unknown $string
     * @return unknown
     */
    public function _filter_slug( $string ) {
        $string = preg_replace('/[\W_]/', '-', $string);
        return $string;
    }


    /**
     *
     *
     * @return unknown
     */
    public function appendix() {
        $appendix = $this->get('appendix');

        if ( is_string( $appendix ) ) {
            $this->__set( 'appendix', json_decode($appendix));
        }

        if ( empty($appendix) ) {
            $this->__set( 'appendix', new StdClass() );
        }

        return $this->get('appendix');
    }


    /**
     *
     *
     * @param unknown $appendix
     * @return unknown
     */
    public function _filter_appendix( $appendix ) {
        if ( is_object( $appendix ) ) {
            return json_encode($appendix);
        }
    }


    /**
     *
     *
     * @param array   $args (optional)
     * @return unknown
     */
    public function content( array $args = array() ) {
        return $this->has_many( 'Pico_Model_ItemContent', array(
                'item_id' => 'id'
            ), $args
        );
    }


    /**
     *
     *
     * @param array   $args (optional)
     * @return unknown
     */
    public function images( array $args = array() ) {
        return $this->has_many_to_many( 'images', 'Pico_Schema_ImageLabel',
            array( 'label_id' => 'id' ),
            array_merge( $args, array('order' =>
                    array( 'image_label' => 'priority')))
        );
    }


    /**
     *
     *
     * @param array   $args (optional)
     * @return unknown
     */
    public function labels( array $args = array() ) {
        return $this->has_many_to_many( 'labels', 'Pico_Schema_ImageLabel',
            array( 'image_id' => 'id' ),
            array_merge( $args, array('order' =>
                    array( 'image_label' => 'priority')))
        );
    }


}
