<?php
class Model_Image extends Model_Item{
    public function __construct( $args = array() ){
        $this->_type = self::ITEM_TYPE_IMAGE;
        parent::__construct( $args );
    }

    public function getLabels(){
        return $this->fetchLabels();
    }

    public function fetchData( $type = null ){
        if( null !== $this->_id ){
            $data = new Model_ImageData(array(
                'imageId' => $this->_id
            ));

            if( null == $type ){
                $type = Model_ImageData::TYPE_ORIGINAL;
            }
            else if( ! is_numeric($type) ){
                $type = $data->getTypeId($type);
            }

            $data->type = $type;

            return $data;
        }
    }

    public function fetchLabels( $limit = 10, $offset = 0 ){
        $search = new Model_ImageLabel();

        $search->imageId = $this->_id;
        $search->setLimit( $limit );
        $search->setOffset( $offset );

        return $search->find();
    }

    public function setLabels( array $labels ){
        $search = new Model_ImageLabel();

        $search->delete( array( 'image_id' => $this->_id ) );

        foreach( $labels as $i => $label ){
            $instance = new Model_Item( array('id'=>$label) );

            if( null == $instance->name ){
                $label = null;
            }

            $labels[$i] = $label;
        }

        $search->save( array(
            'label_id' => array_filter($labels),
            'image_id' => $this->_id
        ));
    }

}
