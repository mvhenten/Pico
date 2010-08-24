<?php
/**
 * class Model_ImageData
 * @package Pico_Model
 */
class Model_ImageData extends Model_Item{
    const FETCH_TABLENAME   = 'image_data';
    const FETCH_PRIMARY_KEY = 'image_id';

    protected $_properties = array(
        'type'  => 1
    );

    public static function get( $key = null, $name = __CLASS__ ){
        return parent::get( $key, $name );
    }
}


//<?php
//class Model_ImageData extends Pico_Model{
//    const IMAGESIZE_THUMBNAIL = '96x96';
//    const IMAGESIZE_ICON      = '32x32';
//    const IMAGESIZE_VIGNETTE   = '400x300';
//
//    const TYPE_ORIGINAL       = 1;
//    const TYPE_VIGNETTE       = 2;
//    const TYPE_THUMBNAIL      = 3;
//    const TYPE_ICON           = 4;
//    const TYPE_CUSTOM         = 5;
//
//    protected $_id;
//    protected $_imageId;
//    protected $_size;
//    protected $_width;
//    protected $_height;
//    protected $_type;
//    protected $_mime;
//    protected $_filename;
//    protected $_data;
//    protected $_created;
//
//    private $image;
//    private $types;
//
//    protected $tableName = 'image_data';
//
//    protected function find(){
//        if( null !== $this->_id ){
//            $this->getMapper()->find( $this );
//        }
//        else if( null !== $this->_imageId && null !== $this->_type ){
//            $values = $this->getMapper()->search( $this, 1 );
//
//            if( $values && $data = reset($values) ){
//                foreach($data->toArray() as $key => $value ){
//                    $this->__set( $key, $value );
//                }
//            }
//            else if( $this->_type != self::TYPE_ORIGINAL ){
//                $this->createImageType();
//            }
//        }
//    }
//
//
//    private function createImageType(){
//        $original = new Model_ImageData();
//        $original->imageId = $this->_imageId;
//        $original->type    = self::TYPE_ORIGINAL;
//
//        if( null !== $original->data ){
//            $gd = new Nano_Gd( $original->data, false );
//
//            switch( $this->_type ){
//                case self::TYPE_ICON:
//                    list( $width, $height ) = explode( 'x', self::IMAGESIZE_ICON );
//                    break;
//                case self::TYPE_THUMBNAIL:
//                    list( $width, $height ) = explode( 'x', self::IMAGESIZE_THUMBNAIL );
//                    break;
//                case self::TYPE_VIGNETTE:
//                    list( $width, $height ) = explode( 'x', self::IMAGESIZE_VIGNETTE );
//                    list( $x, $y ) = array_values($gd->getDimensions());
//
//                    $width = ( $x > $y ) ? $width : ( $height / $y ) * $x;
//                    $height = ( $x < $y ) ? $height : ( $width / $x ) * $y;
//                    break;
//                default:
//                    die( 'Image not found!');
//            }
//
//            $target = $gd->resize( $width, $height );
//
//            $data   = $target->getImageJPEG();
//
//            preg_match( '/^(\w+)\.(\w+)?/', $original->filename, $match );
//
//            list(,$base, $ext) = $match;
//
//            $this->data     = $target->getImageJPEG();
//            $this->size     = strlen($data);
//            $this->mime     = 'image/jpeg';
//            $this->width    = $width;
//            $this->height   = $height;
//            $this->filename = sprintf('%s_%d.%s', $base, $this->_type, $ext);
//
//            $this->save();
//        }
//    }
//
//    public function getTypeId( $type ){
//        $types = $this->getTypes();
//
//        if( key_exists( $type, $types ) ){
//            return $types[$type];
//        }
//
//        return self::TYPE_ORIGINAL;
//    }
//
//    private function getTypes(){
//        if( null == $this->types ){
//            $this->types = array(
//                'original'  => self::TYPE_ORIGINAL,
//                'vignette'  => self::TYPE_VIGNETTE,
//                'thumbnail' => self::TYPE_THUMBNAIL,
//                'icon'      => self::TYPE_ICON,
//            );
//        }
//
//        return $this->types;
//    }
//}
