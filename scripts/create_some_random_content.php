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

function lorem_ipsum( $n_words ) {
    static $words;
    
    if( $words == null ) {
        $ipsum = 'Lorem ipsum dolor sit amet consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua Ut enim ad minim veniam quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur Excepteur sint occaecat cupidatat non proident sunt in culpa qui officia deserunt mollit anim id est laborum';
        $ipsum = strtolower( $ipsum );
        $words = preg_split( '/\W/', $ipsum );        
    }
    
    shuffle( $words );    
    $random_words = array_slice( $words, 0, $n_words );
    return join( ' ', $random_words );
}


function create_item( $type, $name, $priority = 1 ){
    $item = new Pico_Model_Item(array(
        'name'     => $name,
        'visible'   => 1,
        'type'      => $type,
        'priority'  => $priority,
        'inserted'  => date('Y-m-d H:i:s'),
        'slug'      => preg_replace( '/[\s_\W]/', '-', $name ),
        'description' => lorem_ipsum( 10 )
    ));

    $item->store();

    $content = new Pico_Model_ItemContent(
        array(
            'item_id' => $item->id,
            'value'   => lorem_ipsum( 30 )
        )
    );
    
    $content->store();
    
    printf( "Create item: %s: %s, %d\n", $type, $name, $priority );
}

foreach( array( 'contact', 'nieuws', 'info', 'in de media', 'werk', 'links' ) as $name ){
    create_item( 'page', $name );
}


foreach( range( 1, 11 ) as $priority ) {
        
    create_item( 'label', lorem_ipsum( rand( 3, 5 ) ), $priority );
}