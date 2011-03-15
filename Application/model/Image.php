<?php
/**
 * class Model_Image
 * @package Pico_Model
 */
class Model_Image extends Model_Item{
    const FETCH_TABLENAME   = 'item';
    const FETCH_PRIMARY_KEY = 'id';

    private $_labels = null;

    protected $_properties = array(
        'type'  => 1
    );

    public static function get( $key = null, $name = __CLASS__ ){
        return parent::get( $key, $name );
    }

    public function labels(){
        if( $this->_labels == null ){
            $labels = new Nano_Db_Query( new Model_Item() );
            $labels->query('
                SELECT * FROM `item`
                LEFT JOIN `image_label`
                    ON `image_label`.`label_id` = `item`.`id`
                    AND `image_label`.`image_id` = :id
                WHERE `item`.`type` =  "label"
            ', array(':id'=>$this->id));

            $this->_labels = $labels;
        }
        return $this->_labels;
    }

    public function setLabels( $labels ){
        $qh = new Nano_Db_Query( new Model_Item() );

        $qh->query('
            DELETE FROM `image_label` WHERE image_id = :id
        ', array(':id'=>$this->id));

        if( !empty($labels) ){
            $insert = array();
            foreach($labels as $label_id){
                $insert[] = sprintf( "(%d,%d)", $label_id, $this->id );
            }

            $qh->query('
                INSERT INTO image_label
                (label_id, image_id)
                VALUES ' . join(",", $insert), array()
            );
        }
    }
}
