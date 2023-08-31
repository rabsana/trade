<?php

namespace Rabsana\Trade\Tests\Unit;

use Rabsana\Trade\Helpers\Math;
use Rabsana\Trade\Tests\TestCase;
use TypeError;
use ErrorException;

class MathHelpersUnitTest extends TestCase
{

    public function test_convert_scientific_number_math_helper()
    {
        $this->assertEquals(Math::convertScientificNumber(11), 11.0000000000);
        $this->assertEquals(Math::convertScientificNumber(11.11), 11.1100000000);
        $this->assertEquals(Math::convertScientificNumber(1.1e+8), 110000000.0000000000);
        $this->assertEquals(Math::convertScientificNumber(1.1e-8), 0.0000000110);
        $this->assertEquals(Math::convertScientificNumber(1.1e-8, 8), 0.00000001);
        $this->expectException(TypeError::class);
        Math::convertScientificNumber("test");
    }

    public function test_number_math_helper()
    {
        $this->assertEquals(Math::number(11.000001000), 11.000001);
        $this->assertEquals(Math::number(1.1e+8), 110000000);
        $this->assertEquals(Math::number(1.1e+8, 10, false), 110000000.0000000000);
        $this->expectException(TypeError::class);
        Math::number("test");
    }

    public function test_add_two_numbers_math_helper()
    {
        $this->assertEquals(Math::add(5, 6), 11);
        $this->assertEquals(Math::add(1.1e+8, 1.1e-8), 110000000.0000000149);
        $this->assertEquals(Math::add(5.599, 5.499, 0), 11);
        $this->assertEquals(Math::add(5.599, 5.499, 4), 11.098);
    }

    public function test_subtract_two_numbers_math_helper()
    {
        $this->assertEquals(Math::subtract(5, 6), -1);
        $this->assertEquals(Math::subtract(1.1e+8, 1.1e-8), 109999999.9999999851);
        $this->assertEquals(Math::subtract(5.599, 5.499, 0), 0.0);
        $this->assertEquals(Math::subtract(5.599, 5.499, 4), 0.1);
    }

    public function test_multiply_two_numbers_math_helper()
    {
        $this->assertEquals(Math::multiply(5, 6), 30);
        $this->assertEquals(Math::multiply(5.125, 6.11, 1), 31.3);
        $this->assertEquals(Math::multiply(1.1e+8, 1.1e-8), 1.21);
    }

    public function test_divide_two_numbers_math_helper()
    {
        $this->assertEquals(Math::divide(50, 0.4354, 0), 114);
        $this->assertEquals(Math::divide(361, 1.15, 0), 313);
        $this->assertEquals(Math::divide(5, 6), 0.8333333333);
        $this->assertEquals(Math::divide(1.1e+8, 1.1e-8), 10000000000000000);
        $this->expectException(ErrorException::class);
        Math::divide(5, 0);
    }

    public function test_modulus_two_numbers_math_helper()
    {
        $this->assertEquals(Math::modulus(5, 6), 5);
        $this->assertEquals(Math::modulus(1.1e+8, 1.1e-8), 0);
        $this->expectException(ErrorException::class);
        Math::divide(5, 0);
    }

    public function test_greater_than_numbers_math_helper()
    {
        $this->assertEquals(Math::greaterThan(1.1e+8, 1.1e-8), true);
        $this->assertEquals(Math::greaterThan(1.1e+8, 1.1e+8), false);
        $this->assertEquals(Math::greaterThan(1.1e-8, 1.1e+8), false);
    }

    public function test_greater_than_or_equal_numbers_math_helper()
    {
        $this->assertEquals(Math::greaterThanOrEqual(1.1e+8, 1.1e-8), true);
        $this->assertEquals(Math::greaterThanOrEqual(1.1e+8, 1.1e+8), true);
        $this->assertEquals(Math::greaterThanOrEqual(1.1e-8, 1.1e+8), false);
    }

    public function test_less_than_numbers_math_helper()
    {
        $this->assertEquals(Math::lessThan(1.1e-8, 1.1e+8), true);
        $this->assertEquals(Math::lessThan(1.1e+8, 1.1e+8), false);
        $this->assertEquals(Math::lessThan(1.1e+8, 1.1e-8), false);
    }

    public function test_less_than_or_equal_numbers_math_helper()
    {
        $this->assertEquals(Math::lessThanOrEqual(1.1e-8, 1.1e+8), true);
        $this->assertEquals(Math::lessThanOrEqual(1.1e+8, 1.1e+8), true);
        $this->assertEquals(Math::lessThanOrEqual(1.1e+8, 1.1e-8), false);
    }

    public function test_equal_numbers_math_helper()
    {
        $this->assertEquals(Math::equal(1.1e-8, 1.1e+8), false);
        $this->assertEquals(Math::equal(1.1e+8, 1.1e+8), true);
        $this->assertEquals(Math::equal(1.1e+8, 1.1e-8), false);
    }

    public function test_decimal_place_number_helper()
    {
        $this->assertEquals(Math::decimalPlaceNumber(0.000000000100), 10);
        $this->assertEquals(Math::decimalPlaceNumber(1), 0);
    }
}
