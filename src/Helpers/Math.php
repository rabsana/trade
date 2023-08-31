<?php

namespace Rabsana\Trade\Helpers;

class Math
{




    public static function instance(...$args): Math
    {
        return new static(...$args);
    }




    public static function convertScientificNumber(float $number, int $precision = 10): string
    {
        return (string) sprintf("%.{$precision}f", floatval($number));
    }




    public static function number(float $number, int $precision = 10, bool $trimTrailingZeroes = true, bool $numberFormat = false): string
    {
        $number = self::convertScientificNumber($number);

        $explode = explode('.', $number);
        $real = $explode[0] ?? 0;
        $decimal = substr($explode[1] ?? 0, 0, $precision);
        $number = $real . "." . $decimal;

        if ($numberFormat) {
            $number = number_format($number, $precision);
        }

        if ($trimTrailingZeroes) {
            $number = strpos($number, '.') !== false ? rtrim(rtrim($number, '0'), '.') : $number;
        }

        return (string) $number;
    }



    public static function numberFormat(float $number): string
    {
        return self::number($number, 10, true, true);
    }




    public static function add(float $a, float $b, int $precision = 10, bool $trimTrailingZeroes = true): string
    {
        return (string) self::number(

            bcadd(
                self::convertScientificNumber($a),
                self::convertScientificNumber($b),
                $precision
            ),
            $precision,
            $trimTrailingZeroes
        );
    }




    public static function subtract(float $a, float $b, int $precision = 10, bool $trimTrailingZeroes = true): string
    {
        return (string) self::number(

            bcsub(
                self::convertScientificNumber($a),
                self::convertScientificNumber($b),
                $precision
            ),
            $precision,
            $trimTrailingZeroes
        );
    }




    public static function multiply(float $a, float $b, int $precision = 10, bool $trimTrailingZeroes = true): string
    {
        return (string) self::number(

            bcmul(
                self::convertScientificNumber($a),
                self::convertScientificNumber($b),
                $precision
            ),
            $precision,
            $trimTrailingZeroes
        );
    }




    public static function divide(float $a, float $b, int $precision = 10, bool $trimTrailingZeroes = true): string
    {
        return (string) self::number(

            bcdiv(
                self::convertScientificNumber($a),
                self::convertScientificNumber($b),
                $precision
            ),
            $precision,
            $trimTrailingZeroes
        );
    }




    public static function modulus(float $a, float $b, int $precision = 10, bool $trimTrailingZeroes = true): string
    {
        return (string) self::number(

            bcmod(
                self::convertScientificNumber($a),
                self::convertScientificNumber($b),
                $precision
            ),
            $precision,
            $trimTrailingZeroes
        );
    }




    public static function greaterThan(float $a, float $b, int $precision = 10, bool $trimTrailingZeroes = true): bool
    {
        return (bool)(bccomp(
            self::convertScientificNumber($a),
            self::convertScientificNumber($b),
            $precision
        ) == 1);
    }




    public static function greaterThanOrEqual(float $a, float $b, int $precision = 10, bool $trimTrailingZeroes = true): bool
    {
        return (bool)(bccomp(
            self::convertScientificNumber($a),
            self::convertScientificNumber($b),
            $precision
        ) != -1);
    }




    public static function lessThan(float $a, float $b, int $precision = 10, bool $trimTrailingZeroes = true): bool
    {
        return (bool)(bccomp(
            self::convertScientificNumber($a),
            self::convertScientificNumber($b),
            $precision
        ) == -1);
    }




    public static function lessThanOrEqual(float $a, float $b, int $precision = 10, bool $trimTrailingZeroes = true): bool
    {
        return (bool)(bccomp(
            self::convertScientificNumber($a),
            self::convertScientificNumber($b),
            $precision
        ) != 1);
    }




    public static function equal(float $a, float $b, int $precision = 10, bool $trimTrailingZeroes = true): bool
    {
        return (bool)(bccomp(
            self::convertScientificNumber($a),
            self::convertScientificNumber($b),
            $precision
        ) == 0);
    }



    public static function notEqual(float $a, float $b, int $precision = 10, bool $trimTrailingZeroes = true): bool
    {
        return (bool)!self::equal($a, $b, $precision, $trimTrailingZeroes);
    }




    public static function decimalPlaceNumber(float $decimal): int
    {
        return (int) strlen(substr(strrchr(self::number($decimal), "."), 1));
    }



    // 
}
