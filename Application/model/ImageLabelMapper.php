<?php
class Model_ImageLabelMapper extends Nano_Db_Mapper{
    public function __construct(){
        parent::__construct( 'image_label' );
    }

    /**
     * Delete this object from the database using it's primary key
     * @param Some_Model $model Model to delete
     * @return void
     */
    public function delete( $what ){
        $where = array();
        $values = array();


        foreach( $what as $key => $value ){
            if( $key != 'label_id' && $key != 'image_id' ) continue;

            $value = array_map('intval', (array) $value );



            $value = join( ',', $value );
            $where[] = sprintf('%s IN (%s)', $key, $value );
        }

        $this->getDb()->query(sprintf('
            DELETE FROM `%s` WHERE %s
           ', $this->_tableName
            , join( 'AND ', $where )
        ));
    }

    public function save( $what ){
        $images = (array) $what['image_id'];
        $labels = (array) $what['label_id'];
        $values = array();

        foreach( $images as $image ){
            foreach( $labels as $label ){
                $values[] = sprintf('(%d, %d)', $image, $label);
            }
        }

        $query = sprintf('
            INSERT INTO image_label
            ( image_id, label_id )
            VALUES %s
        ', join( ',', $values ));

        $this->getDb()->query( $query );
    }

    public function nsave( $model ){
        $values = $model->toArray();
        $key 	= $this->_primaryKey;

        $keys 	= array_map( array( $this, '_dasherize' ), array_keys($values) );
        $values = array_combine( $keys, $values );

        if( null == $model->$key ){
            $id = $this->getDb()->insert( $this->_tableName, $values );
            $model->$key = $id;
        }
        else{
            $this->getDb()->update( $this->_tableName, $values, $key );
        }
    }

    public function search( Model_ImageLabel $search ){
        $where = array();

        $query = array('
            SELECT i.*
            FROM image_label il
        ');

        if( null !== ( $id = $search->imageId ) ){
            $query[] = 'LEFT JOIN item i ON il.label_id = i.id';
            $query[] = 'WHERE image_id = :image_id';

            $where = array(':image_id' => $id );
            $class = 'Model_Label';
        }
        else if( null !== ( $id = $search->labelId ) ){
            $query[] = 'LEFT JOIN item i ON il.image_id = i.id';
            $query[] = 'WHERE label_id = :label_id';

            $where = array(':label_id' => $id );
            $class = 'Model_Image';
        }
        else{
            throw new Exception( 'No image_id or label_id set!');
        }

        $query[] = sprintf('LIMIT %d OFFSET %d', $search->getLimit(), $search->getOffset() );

        $results = $this->getDb()->fetchAll( join( "\n", $query), $where );

        foreach( $results as $i => $result ){
            $results[$i] = new $class( $result );
        }

        return $results;
    }
}
