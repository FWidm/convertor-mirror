<?php

use Olifolkerd\Convertor\Convertor;
use Olifolkerd\Convertor\Exceptions\ConvertorException;
use PHPUnit\Framework\TestCase;

class DistanceTest extends TestCase
{
    /** @test */
    public function testDistance()
    {
        $conv = new Convertor(1, "KM");
        $this->assertEquals(1000, $conv->to("m"));
        $this->assertLessThanOrEqual(3280.9, $conv->to("ft"));
    }

    /** @test */
    public function testUnitDoesNotExist()
    {
        $this->expectException(ConvertorException::class);
        new Convertor(1, "nonsenseunit");
    }

}
