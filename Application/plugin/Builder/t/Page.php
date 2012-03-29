<?php
/**
 * Application/plugin/Builder/t/Page.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */

define( 'NANO_ROOT', dirname(__FILE__) . '../../../../../../');

class Builder_PageTest extends PHPUnit_Framework_TestCase{
    private $config;

    /**
     *
     */
    protected function setUp() {
        require_once NANO_ROOT . 'Nano/library/Nano/Autoloader.php';
        Nano_Autoloader::register();
        Nano_Autoloader::registerNamespace( 'Builder', dirname(__FILE__) . '/../lib'  );
    }



    /**
     *
     */
    public function testSmoke() {
        $builder = new Builder_Page(array(
                'head'     => array(
                    'children' => array(
                        array(
                            'type' => 'link',
                            'attributes' => array(
                                'href' => 'style.css',
                                'rel'  => 'stylesheet',
                            ))
                    )
                ),
                'body' => array(
                    'children' => array(
                        array(
                            'type' => 'block',
                        )
                    )
                )
            ));

        echo $builder;
    }


}
