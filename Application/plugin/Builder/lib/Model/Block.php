<?php
class Builder_Model_Block extends Builder_Schema_Block {

    public function children( array $args = array() ){
        return $this->has_many( 'Builder_Model_Block',  array(
           'parent_id'         => 'id',
        ), $args );
    }

    public function parent( array $args = array() ){
        return $this->has_one( 'Builder_Model_Block', array(
                'id' => 'parent_id'
            ), $args
        );
   }

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

    public function _filter_data( $data ) {
        if ( is_object( $data ) ) {
            return json_encode($data);
        }
    }

}
