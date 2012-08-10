<?php
/**
 * Application/lib/Test/Db.php
 *
 * Test plumbing - use Pico_Test_Db::load_pico_schema() to initialize
 * an empty in-memory database with pico tables.
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


class Pico_Test_Db {
    const SQLITE_SCHEMA_FILE = 'sql/pico-sqlite.sql';

    /**
     * initialize Nano_Db with sqlite memory driver
     */
    public static function init_db() {
        Nano_Db::setAdapter( array( 'dsn' => 'sqlite::memory:' ) );
    }


    /**
     * reads pico sql schema and creates all pico tables
     */
    public static function load_pico_schema() {
        self::init_db();
        $schema_path = join( '/', array( APPLICATION_ROOT, self::SQLITE_SCHEMA_FILE ));
        $contents    = file_get_contents( $schema_path );

        $dbh = Nano_Db::getAdapter();

        foreach ( explode( ';', $contents ) as $sql ) {
            $dbh->query( $sql );
        }
    }


}
