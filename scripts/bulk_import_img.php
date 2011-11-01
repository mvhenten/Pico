#!/usr/bin/env php
<?php
define( "APPLICATION_ROOT", dirname(__FILE__) ); // the root of the application
define( "APPLICATION_PATH", dirname(APPLICATION_ROOT)); //where the application is

require_once( APPLICATION_PATH . '/../Nano/library/Nano/Autoloader.php');
Nano_Autoloader::register();
Nano_Autoloader::registerNamespace( 'Pico', APPLICATION_PATH . '/Application/lib');

$config  = new Nano_Config_Ini( APPLICATION_PATH . '/Application/config/application.ini' );


Nano_Db::setAdapter( $config->database );


Nano_Autoloader::registerNamespace( 'Model', APPLICATION_PATH . '/Application/Model' );



$files = array_slice( $argv, 1 );  //is_dir( $argv[1] ) ? scandir( $argv[1] ) : null;

$iter = 0;
$images = array();

$label_name = 'import ' . basename(dirname( $files[0] )) . ' on ' . date('Y-m-d H:m:s');

function rotate_img( Nano_Exif $exif, Nano_Gd $gd ){
    switch( $exif->orientation() ){
        case 2:
            return $gd->flipHorizontal();
        case 3:
            return $gd->rotate( 180 );
        case 4:
            return $gd->flipVertical();
        case 5:
            return $gd->flipVertical()->rotate(90);
        case 6:
            return $gd->rotate( -90 );
        case 7:
            return $gd->flipHorizontal()->rotate( -90 );
        case 8:
            return $gd->rotate( 90 );
    }

    return $gd;
}

foreach( $files as $iter => $file ){
    printf( "Working on file %d of %d: %s\n", $iter, count($files)-1, basename($file) );
    
    $info = Nano_Gd::getInfo( $file );
    
    $gd  = new Nano_Gd( $file );
    $src = $gd->getImageJPEG();

    list( $width, $height ) = array_values( $gd->getDimensions() );

    $exif = new Nano_Exif( $file );
    $gd = rotate_img( $exif, $gd );
    
    $name = strtolower(current(explode('.', basename($file))));

    $image = new Pico_Model_Item(array(
        'name'     => $name,
        'visible'   => 0,
        'type'      => 'image',
        'inserted'  => date('Y-m-d H:i:s')
    ));

    $image->slug = preg_replace( '/\W+/', '-', basename($file));
    $id = $image->store();


    if( $info[2] == IMAGETYPE_PNG ){
        $src = $gd->getImagePNG();
    }
    else{
        $src = $gd->getImageJPEG();
        $type = 'image/jpeg';
    }

    $data = new Pico_Model_ImageData(array(
        'image_id'  => $image->id,
        'size'      => filesize($file),
        'mime'      => $type,
        'width'     => $width,
        'height'    => $height,
        'data'      => $src,
        'filename'  => strtolower(basename($file)),
        'type'      => 'original'
    ));

    $data->store();

    array_push( $images, $image->id );
}


$label = new Pico_Model_Item(array(
    'name'     => $label_name,
    'visible'   => 0,
    'type'      => 'label',
    'inserted'  => date('Y-m-d H:i:s')
));

$label->slug = preg_replace( '/[\s_\W]/', '-', $label_name );
$label->store();


foreach( $images as $img_id ){
    $insert = new Pico_Schema_ImageLabel(array(
        'label_id' => $label->id,
        'image_id' => $img_id,
        'priority' => 100,
    ));
    $insert->store();
}
