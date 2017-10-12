<?php

use Olifolkerd\Convertor\Convertor;
use Olifolkerd\Convertor\Exceptions\ConvertorException;
use Olifolkerd\Convertor\Exceptions\ConvertorInvalidUnitException;
use PHPUnit\Framework\TestCase;

//todo: add tests for all other conversions.

/**
 * Class Test
 * Provides tests for the convertor to make sure conversions are fine
 * Currently tested unit groups are:
 * - Temperature
 * - Weight
 * - Pressure
 * - Area density
 * - Speeds
 * wip:
 * - Distance/Length
 * todo:
 * - area
 * - volume
 * - time
 * - power
 */
class Test extends TestCase
{

    /** @test */
    public function testTemperature()
    {
        $conv = new Convertor();
        $conv->from(0,'c');
        $val=$conv->toAll(2);

        $this->assertEquals(32,$val['f'] );
        $this->assertEquals(273.15,$val['k']);
        $this->assertEquals(0,$val['c'] );
    }

    /** @test */
    public function testWeight()
    {
        $conv = new Convertor();
        $conv->from(100,'g');
        $val=$conv->toAll(6,true);
        $this->assertEquals(100,$val['g'] );
        $this->assertEquals(.1,$val['kg'] );
        $this->assertEquals(100000,$val['mg'] );
        $this->assertEquals(0.220462,$val['lb'],"Not inside of float delta",0.00001);
        $this->assertEquals(1e-4,$val['t']);
        $this->assertEquals(3.527400,$val['oz'],"Not inside of float delta",0.00001);
        $this->assertEquals(0.0157473,$val['st'],"Not inside of float delta",0.00001);
        $this->assertEquals(0.0157473,$val['st'],"Not inside of float delta",0.00001);
        $this->assertEquals(0.9806649999787735,$val['N'],"Not inside of float delta",0.00001);
    }

    /** @test */
    public function testPressure()
    {
        $conv = new Convertor();
        $conv->from(100,'pa');
        $val=$conv->toAll(6,true);
        // http://convert-units.info/pressure/hectopascal/1
        $this->assertEquals(100,$val['pa'],"Not inside of float delta",0.00001);
        $this->assertEquals(1,$val['hpa'],"Not inside of float delta",0.00001);
        $this->assertEquals(.1,$val['kpa'],"Not inside of float delta",0.00001);
        $this->assertEquals(0.0001,$val['mpa'],"Not inside of float delta",0.00001);
        $this->assertEquals(0.001,$val['bar'],"Not inside of float delta",0.00001);
        $this->assertEquals(1,$val['mbar'],"Not inside of float delta",0.00001);
        $this->assertEquals(0.0145038,$val['psi'],"Not inside of float delta",0.00001);
    }

    /** @test */
    public function testAreaDensity()
    {
        $conv = new Convertor();
        $conv->from(1,'kg m**-2');
        $val=$conv->toAll(6,true);
        $this->assertEquals(1,$val['kg m**-2'],"Not inside of float delta",0.00001);
        $this->assertEquals(1000000,$val['kg km**-2'],"Not inside of float delta",0.00001);
        $this->assertEquals(1e-4,$val['kg cm**-2'],"Not inside of float delta",0.00001);
        $this->assertEquals(1e-6,$val['kg mm**-2'],"Not inside of float delta",0.00001);
        $this->assertEquals(1000,$val['g m**-2'],"Not inside of float delta",0.00001);
        $this->assertEquals(1000000,$val['mg m**-2'],"Not inside of float delta",0.00001);
        $this->assertEquals(0.157473,$val['st m**-2'],"Not inside of float delta",0.00001);
        $this->assertEquals(2.20462,$val['lb m**-2'],"Not inside of float delta",0.00001);
        $this->assertEquals(35.274,$val['oz m**-2'],"Not inside of float delta",0.00001);
    }
    /** @test */
    public function testSpeeds()
    {
        $conv = new Convertor();
        $conv->from(3,'km h**-1');
        $val=$conv->toAll(6,true);
        $this->assertEquals(0.83333,$val['m s**-1'],"Not inside of float delta",0.00001);
        $this->assertEquals(3,$val['km h**-1'],"Not inside of float delta",0.00001);
        $this->assertEquals(1.86411,$val['mi h**-1'],"Not inside of float delta",0.00001);
        $conv->from(100,'m s**-1');
        $val=$conv->toAll(3,true);
        $this->assertEquals(100,$val['m s**-1'],"Not inside of float delta",0.00001);
        $this->assertEquals(360,$val['km h**-1'],"Not inside of float delta",0.00001);
        $this->assertEquals(223.694,$val['mi h**-1'],"Not inside of float delta",0.0001);
    }

    /** @test */
    public function testDistance()
    {
        $conv = new Convertor(1, "km");
        $this->assertEquals(1000, $conv->to("m"));
        $this->assertLessThanOrEqual(3280.84, $conv->to("ft"));
    }

    /** @test */
    public function testUnitDoesNotExist()
    {
        $this->expectException(ConvertorInvalidUnitException::class);
        new Convertor(1, "nonsenseunit");
    }

    /** @test */
    public function testBaseConstructor()
    {
        $c = new Convertor();
        $c->from(6.16, 'ft');
        $this->assertEquals(1.87757, $c->to('m', 5));
    }
}