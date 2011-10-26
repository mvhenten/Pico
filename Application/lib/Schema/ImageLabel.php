<?
class Pico_Schema_ImageLabel extends Nano_Db_Schema {
    protected $_tableName = 'image_label';

    protected $_schema = array(
        'image_id' => array(
            'type'      => 'int',
            'length'    => 11,
            'default'   => '',
            'name'      => 'image_id',
            'extra'     => '',
            'required'  => true,
        ),

        'label_id' => array(
            'type'      => 'int',
            'length'    => 11,
            'default'   => '',
            'name'      => 'label_id',
            'extra'     => '',
            'required'  => true,
        ),

        'priority' => array(
            'type'      => 'int',
            'length'    => 10,
            'default'   => '',
            'name'      => 'priority',
            'extra'     => '',
            'required'  => true,
        )
    );

    protected $_primary_key = array(
        'image_id','label_id'
    );

    public function images( array $args = array() ){
        return $this->belongs_to( 'Pico_Model_Item', array( 'id' => 'image_id' ), $args );
    }

    public function labels( array $args = array() ){
        return $this->belongs_to( 'Pico_Model_Item', array( 'id' => 'label_id' ), $args );
    }
}
