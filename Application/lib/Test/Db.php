<?php
class Pico_Test_Db {
    const SQLITE_SCHEMA_FILE = 'sql/pico-sqlite.sql';
    const APPLICATION_PATH   = dirname(dirname(dirname(__FILE__)));

    
    public static function init_db(){
        Nano_Db::setAdapter( array( 'dsn' => 'sqlite::memory:' ) );    
    }

    public static function load_pico_schema () {
        self::init_db();
        $schema_path = join( '/', self::APPLICATION_PATH, self::SQLITE_SCHEMA_FILE );
        $contents    = file_get_contents( $schema_path );
        
        $dbh = Nano_Db::getAdapter();

        foreach ( explode( ';', $contents ) as $sql ){
            $dbh->query( $sql );            
        }        
    }
}