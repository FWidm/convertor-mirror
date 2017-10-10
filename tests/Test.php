<?php

use Olifolkerd\Convertor\Convertor;
use Olifolkerd\Convertor\Exceptions\ConvertorException;
use PHPUnit\Framework\TestCase;

/**
 * User: Fabian Widmann
 * Date: 10.10.17
 * Time: 15:23
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

    //todo: add tests for all other conversions.

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
        $this->expectException(ConvertorException::class);
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