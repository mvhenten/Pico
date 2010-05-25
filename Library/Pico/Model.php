<?php
/**
 * Base class for slicr models
 *
 * Since all models basically have the same design, this class holds the common
 * funcitons.
 */
abstract class Pico_Model{
    /**
     * Placeholder variable for the Mapper object
     * @var Object $mapper
     */
    private $mapper;

    /**
     * Placeholder for the database table this model refers to
     * If this name is not defined, this Model_Mapper is loaded
     * @var string $tableName
     */
    protected $tableName;

    /**
     * @param mixed $args Any number of arguments may be used to initialize
     * 						Args are passed to class __set method
     */
    public function __construct( $args = array() ){
        $args = (array) $args;
        foreach( $args as $key => $value ){
            $this->__set( $key, $value );
        }
    }

	/**
	 * magic seter: may get values to be saved or retrieved from db record
	 * - Convert between under_scores and camelCase
	 * - Calls setMethod if defined
	 *
	 *	@param string $name Name of the property ( camelcase or with_underscores )
	 *	@param mixed $value Value to set
	 *	@return void
	 */
    public function __set( $name, $value ){
        $name = $this->_camelize( $name );

		$property   = sprintf( '_%s', $name );

		if( property_exists( $this, $property ) ){
			if( ( $method = 'set' . ucfirst( $name ) ) && method_exists( $this, $method ) ){
				$this->$method( $value );
			}
			else{
				$this->$property = $value;
			}
		}
		else{
			throw new Exception( sprintf('Trying to set property %s', $property) );
            exit;
		}
        return $this;
    }


	/**
	 * magic getter: may get values retrieved from database record.
	 * - Convert between under_scores and camelCase
	 * - Calls getMethod if defined
	 * - Performs database lookup if values are not set
	 *
	 * @param string $name Name of the value. may be with_underscores or camelCased
	 * @return mixed $value or null
	 */
    public function __get( $name ){
        $name       = $this->_camelize( $name );
		$property   = sprintf( '_%s', ltrim($name, '_') );

		if( property_exists( $this, $property ) ){
			if( ( $method = 'get' . ucfirst( $name ) ) && method_exists( $this, $method ) ){
				return call_user_func( array( $this, $method ) );
			}
			if( null === $this->$property ){
				$this->find();
			}
			return $this->$property;
		}
		else{
			throw new Exception( sprintf('Trying to get property %s', $name) );
		}
    }
    
	/**
	 * returns all class values as an array
	 * @return array $values
	 */
	public function toArray(){
		$collect = array();
		foreach( get_object_vars( $this ) as $key => $value ){
			if( strpos( $key, '_' ) === 0 ){
				$property = str_replace( '_', '', $key );
				$collect[$property] = $this->$key;
			}
		}
		return $collect;
	}

    /**
     * @todo paging
     *
     */
    public function search(){
        return $this->getMapper()
                    ->search( $this );
    }

    /**
     * Perform lookup by id
     *
     * @return bool $succes
     */
    protected function find(){
        if( null !== $this->_id ){
            $this->getMapper()
                ->find( $this );
        }
        return $this;
    }

	/**
     * save this model (basic implementation)
     *
	 * @return $this fluent interface
	 */
    public function save(){
        $this->getMapper()->save( $this );
		return $this;
    }

	/**
     * delete this model (basic implementation)
     *
	 * @return $this fluent interface
	 */
    public function delete(){
        $this->getMapper()->delete( $this );
        return $this;
    }

    /**
     * Fetch or instantiates the mapper
     *
     * @todo Make this more generic as a Nano thing.
     * @return object default_model_mapper
     */
    protected function getMapper(){
        if( $this->mapper === null ){
            if( null !== $this->tableName ){
                $mapper = new Nano_Db_Mapper( $this->tableName );
            }
            else{
                $mapperName = get_class( $this ) . 'Mapper';
                try{
                    $mapper = new $mapperName();
                }
                catch( Exception $e ){
                    die( "Attempted to load $mapperName failed:\n" . $e );
                }
            }
            $this->setMapper( $mapper );
        }

        return $this->mapper;
    }

    /**
     * Set mapper
     *
     * @param default_model_mapper $mapper
     */
    protected function setMapper( $mapper ){
        $this->mapper = $mapper;
        return $this;
    }

    /**
     * utility function: convert name_with_spaces to nameWithCamels
     *
     * @param string $string String to convert
     * @return string $camelized Camelized string.
     */
    private function _camelize( $string ){
        if( property_exists( $this, $string ) ){
            return $string;
        }
        else{
            $pieces = explode( '_', $string ); //camelize
            if( count( $pieces ) > 1 ){
                $string = array_shift( $pieces ) . join('', array_map( 'ucfirst', $pieces ) ); 	//camelize
            }
            return $string;
        }
    }
}
