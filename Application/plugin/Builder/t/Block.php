<?php
/**
 * Application/plugin/Builder/t/Block.php
 *
 * @author Matthijs van Henten <matthijs@ischen.nl>
 * @package Pico
 */


class Builder_PageTest extends PHPUnit_Framework_TestCase{
    private $config;

    /**
     *
     */
    protected function setUp() {
        require_once dirname(dirname(__FILE__)) . '/../Nano/library/Nano/Autoloader.php';
        Nano_Autoloader::register();
        Nano_Autoloader::registerNamespace( 'Builder', dirname(__FILE__) . '/../lib'  );
    }



    /**
     *
     */
    public function testStringify() {
        $builder = new Builder_Block();
        $expect = '	<div>

	</div>
	';

        $this->assertEquals($expect, (string) $builder );
    }


}
