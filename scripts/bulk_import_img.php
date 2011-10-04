#!/usr/bin/env php
<?php
define( "APPLICATION_ROOT", dirname(__FILE__) ); // the root of the application
define( "APPLICATION_PATH", dirname(APPLICATION_ROOT)); //where the application is

require_once( APPLICATION_PATH . '/../Nano/library/Nano/Autoloader.php');
Nano_Autoloader::register();
$config  = new Nano_Config_Ini( APPLICATION_PATH . '/Application/config/application.ini' );


Nano_Db::setAdapter( $config->database );


Nano_Autoloader::registerNamespace( 'Model', APPLICATION_PATH . '/Application/Model' );



$files = array_slice( $argv, 1 );  //is_dir( $argv[1] ) ? scandir( $argv[1] ) : null;
$iter = 0;
$images = array();

$label_name = 'import ' . basename(dirname( $files[0] )) . 'on ' . date('Y-m-d H:m:s');

while( $files && ( $file = array_shift($files))  && ( $info = Nano_Gd::getInfo( $file ))  ){
    $gd  = new Nano_Gd( $file );
    
    $iter++;


    printf( "Working on file %d of %d: %s\n", $iter, count($files), basename($file) );    

    list( $width, $height ) = array_values( $gd->getDimensions() );
    
    $image = new Model_Item(array(
        'name'     => $file,
        'visible'   => 0,
        'type'      => 'image',
        'inserted'  => date('Y-m-d H:i:s')
    ));

    $image->slug = preg_replace( '/\W+/', '-', basename($file));
    $image->put();
    
        
    if( $info[2] == IMAGETYPE_PNG ){
        $src = $gd->getImagePNG();
    }
    else{
        $src = $gd->getImageJPEG();
        $type = 'image/jpeg';
    }
    
    $data = new Model_ImageData(array(
        'image_id'  => $image->id,
        'size'      => filesize($file),
        'mime'      => $type,
        'width'     => $width,
        'height'    => $height,
        'data'      => $src,
        'filename'  => basename($file),
        'type'      => 'original'
    ));
    
    $data->put();
    
    array_push( $images, $image->id );
}


$label = new Model_Item(array(
    'name'     => $label_name,
    'visible'   => 0,
    'type'      => 'label',
    'inserted'  => date('Y-m-d H:i:s')
));

$label->slug = preg_replace( '/\W+/', '-', $label_name );
$label->put();


foreach( $images as $img_id ){
    $insert = new Model_ImageLabel(array(
        'label_id' => $label->id,
        'image_id' => $img_id
    ));
    $insert->put();
}

