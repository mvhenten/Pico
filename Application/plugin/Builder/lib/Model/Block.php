<?php
/**
 * lib/Model/Block.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Builder
 */


class Builder_Model_Block extends Builder_Schema_Block {

    /**
     * Call store with our primary id.
     */
    public function save() {
        $this->store( array( 'id' => $this->id ) );
    }


    /**
     * Return all children of this parent
     *
     * @param array   $args (optional)
     * @return unknown
     */
    public function children( array $args = array() ) {
        return $this->has_many( 'Builder_Model_Block',  array(
                'parent_id'         => 'id',
            ), $args );
    }


    /**
     * Return the parent, if there is one.
     *
     * @param array   $args (optional)
     * @return unknown
     */
    public function parent( array $args = array() ) {
        return $this->has_one( 'Builder_Model_Block', array(
                'id' => 'parent_id'
            ), $args
        );
    }


    /**
     * Post-process the data field - unserialize json
     *
     * @return unknown
     */
    public function data() {
        $data = $this->get('data');

        if ( is_string( $data ) ) {
            $this->__set( 'appendix', json_decode($data));
        }

        if ( empty($data) ) {
            $this->__set( 'appendix', new StdClass() );
        }

        return $this->get('appendix');
    }


    /**
     * Pre-process the data value before storing ( serialize );
     *
     * @param unknown $data
     * @return unknown
     */
    public function _filter_data( $data ) {
        if ( is_object( $data ) ) {
            return json_encode($data);
        }
    }


}
