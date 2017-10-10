<?php
/**
 * User: Fabian Widmann
 * Date: 10.10.17
 * Time: 11:02
 */

use Olifolkerd\Convertor\Convertor;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function testConvertor(){
        $conv=new Convertor(1,"KM");
        $this->assertEquals(1000,$conv->to("m"));
        $this->assertLessThanOrEqual(3280.9,$conv->to("ft"));
    }
}
