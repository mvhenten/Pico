<?php
class Model_ImageLabel extends Pico_Model{
    const DEFAULT_LIMIT = 25;
    const DEFAULT_OFFSET = 0;

    protected $_imageId;
    protected $_labelId;

    protected $limit;
    protected $offset;

    /**
     * Find elements from item by imageId or labelId
     */
    public function find( $limit = 10, $offset = 0 ){
        return $this->getMapper()->find( $this, $limit, $offset );
    }

    public function setLimit( $limit ){
        $this->limit = intval( $limit );
    }

    public function getLimit(){
        if( null == $this->limit ){
            $this->limit = self::DEFAULT_LIMIT;
        }

        return $this->limit;
    }

    public function setOffset( $offset ){
        $this->offset = intval( $offset );
    }

    public function getOffset(){
        if( null == $this->offset ){
            $this->offset = self::DEFAULT_OFFSET;
        }

        return $this->offset;
    }

    public function delete( $what ){
        $this->getMapper()->delete( $what );
    }

    /**
     * Save multiple rows
     *
     * @param what array with keys image_id or label_id
     */
    public function save( $what ){
        $what = array_merge(array(
            'image_id' => null,
            'label_id' => null
        ), $what);

        if( $what['image_id'] != null && $what['label_id'] != null ){
            $this->getMapper()->save( $what );
        }
    }

    public function search( $what ){
        $what = array_merge(array(
            'image_id' => null,
            'label_id' => null
        ), $what);

        if( $what['image_id'] != null || $what['label_id'] != null ){
            return $this->getMapper()->search( $what );
        }
    }

    protected function getImageId(){
        return $this->_imageId;
    }

    protected function getLabelId(){
        return $this->_labelId;
    }
}
