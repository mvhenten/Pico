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

function import_images( $files ){
    $collect = array();

    foreach( $files as $i => $file ){
        if( !getimagesize($file) ) continue;
        printf( "INFO: Working on file %d of %d: %s\n", $i, count($files)-1, basename($file) );
        $image = import_image( $file );

        if( $image ){
            $path_parts = explode( '/', $file );
            @list( $label, $img_file ) = array_slice( $path_parts, -2 );

            if( ! isset( $collect[$label] ) ){
                $collect[$label] = array();
            }

            $collect[$label][] = $image->id;
        }
    }

    foreach( $collect as $label => $image_ids ){
        echo "Create label $label for " . count($image_ids) . " images\n";
        create_label( $label, $image_ids );
    }
}

function import_image( $path ){
    $data = file_get_contents( $path );

    if( ! $data ){
        printf( "WARNING: Cannot import image from '%s'\n", $path );
    }

    $info = getimagesize( $path );

    $name = strtolower(current(explode('.', basename($path))));
    $image = new Pico_Model_Item(array(
        'name'     => $name,
        'visible'   => 0,
        'type'      => 'image',
        'inserted'  => date('Y-m-d H:i:s'),
        'slug'      => preg_replace( '/\W+/', '-', $name )
    ));

    $image->store();
    @list( $width, $height ) = $info;

    $data = new Pico_Model_ImageData(array(
        'image_id'  => $image->id,
        'size'      => strlen($data),
        'mime'      => $info['mime'],
        'width'     => $width,
        'height'    => $height,
        'data'      => $data,
        'filename'  => strtolower(basename($path)),
        'type'      => 'original'
    ));

    $data->store();
    return $image;
}

function create_label( $name, $image_ids ){
    $label = new Pico_Model_Item(array(
        'name'     => $name,
        'visible'   => 0,
        'type'      => 'label',
        'inserted'  => date('Y-m-d H:i:s'),
        'slug'      => preg_replace( '/[\s_\W]/', '-', $name )
    ));

    $label->store();

    foreach( $image_ids as $priority => $id ){
        $insert = new Pico_Schema_ImageLabel(array(
            'label_id' => $label->id,
            'image_id' => $id,
            'priority' => $priority,
        ));

        $insert->store();
    }
}

function main( $argv ){
    @list( $script, $path ) = $argv;

    $files = `find $path -name "*.jpg"`;
    $files = array_filter(explode( "\n", $files ));

    import_images( $files );

    //var_dump( $files );


}

main( $argv );
