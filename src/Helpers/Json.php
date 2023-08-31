<?php

namespace Rabsana\Trade\Helpers;

use stdClass;

class Json
{
    public static function instance(...$args): Json
    {
        return new static(...$args);
    }

    public static function is($string): bool
    {
        return (bool)(is_string($string) &&
            !preg_match(
                '/[^,:{}\\[\\]0-9.\\-+Eaeflnr-u \\n\\r\\t]/',
                preg_replace('/"(\\.|[^"\\\\])*"/', '', $string)
            ));
    }

    public static function encode($mixed, int $flags = 0, int $depth = 512)
    {
        return (is_resource($mixed)) ? '' : ((self::is($mixed)) ? $mixed : json_encode($mixed, $flags, $depth));
    }

    public static function decode($json, $associative = null, int $depth = 512, int $flags = 0)
    {
        return (!is_string($json) || !self::is($json)) ? self::type($json) : json_decode($json, $associative, $depth, $flags);
    }

    public static function type($value)
    {
        return self::types()[gettype($value)];
    }

    public static function types(): array
    {
        return [
            'boolean'       => false,
            'integer'       => 0,
            'double'        => 0,
            'string'        => '',
            'array'         => [],
            'object'        => new stdClass,
            'resource'      => '',
            'NULL'          => NULL,
            'unknown type'  => ''
        ];
    }

    public static function response(int $status = 200, string $message = '', array $data = [], array $errors = [])
    {
        return response()->json([
            'status'                => $status,
            'success'               => ($status >= 200 && $status <= 299) ? true : false,
            'message'               => $message,
            'errors'                => $errors,
            'data'                  => $data
        ], $status);
    }
}
