<?php
/**
 * scripts/bulk_import_img.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


define( "APPLICATION_ROOT", dirname(__FILE__) ); // the root of the application
define( "APPLICATION_PATH", dirname(APPLICATION_ROOT)); //where the application is
ini_set('memory_limit', '16M');

require_once APPLICATION_PATH . '/../Nano/library/Nano/Autoloader.php';
Nano_Autoloader::register();
Nano_Autoloader::registerNamespace( 'Pico', APPLICATION_PATH . '/Application/lib');

$config  = new Nano_Config_Ini( APPLICATION_PATH . '/Application/config/application.ini' );

Nano_Db::setAdapter( $config->database );
Nano_Autoloader::registerNamespace( 'Model', APPLICATION_PATH . '/Application/Model' );


/**
 *
 *
 * @param object  $exif
 * @param object  $gd
 * @return unknown
 */
function rotate_img( Nano_Exif $exif, Nano_Gd $gd ) {
    switch ( $exif->orientation() ) {
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


/**
 *
 *
 * @param unknown $files
 */
function import_images( $files ) {
    $collect = array();

    foreach ( $files as $i => $file ) {
        if ( !getimagesize($file) ) continue;
        printf( "INFO: Working on file %d of %d: %s\n", $i, count($files)-1, basename($file) );
        $image = import_image( $file );

        if ( $image ) {
            $path_parts = explode( '/', $file );
            @list( $label, $img_file ) = array_slice( $path_parts, -2 );

            if ( ! isset( $collect[$label] ) ) {
                $collect[$label] = array();
            }

            $collect[$label][] = $image->id;
        }
    }

    foreach ( $collect as $label => $image_ids ) {
        echo "Create label $label for " . count($image_ids) . " images\n";
        create_label( $label, $image_ids );
    }
}


/**
 *
 *
 * @param unknown $path
 * @return unknown
 */
function import_image( $path ) {
    $name = strtolower(current(explode('.', basename($path))));
    $image = new Pico_Model_Item(array(
            'name'     => $name,
            'visible'   => 0,
            'type'      => 'image',
            'inserted'  => date('Y-m-d H:i:s'),
            'slug'      => preg_replace( '/\W+/', '-', $name )
        ));

    $image->store();

    $filename = strtolower(basename($path));

    $image_data = Pico_Model_ImageData::createFromPath( $path, $filename, $image );

    if ( ! $image_data ) {
        printf( "WARNING: Cannot import image from '%s'\n", $path );
    }

    return $image;
}


/**
 *
 *
 * @param unknown $name
 * @param unknown $image_ids
 */
function create_label( $name, $image_ids ) {
    $label = new Pico_Model_Item(array(
            'name'     => $name,
            'visible'   => 0,
            'type'      => 'label',
            'inserted'  => date('Y-m-d H:i:s'),
            'slug'      => preg_replace( '/[\s_\W]/', '-', $name )
        ));

    $label->store();

    foreach ( $image_ids as $priority => $id ) {
        $insert = new Pico_Schema_ImageLabel(array(
                'label_id' => $label->id,
                'image_id' => $id,
                'priority' => $priority,
            ));

        $insert->store();
    }
}


/**
 *
 *
 * @param unknown $argv
 */
function main( $argv ) {
    @list( $script, $path ) = $argv;


    $files = `find $path -iname "*.jpg"`;
    $files = array_filter(explode( "\n", $files ));

    import_images( $files );

    //var_dump( $files );


}


main( $argv );
