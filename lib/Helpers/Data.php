<?php
namespace Congress\Lib\Helpers;

final class Data
{
    public function __construct() { }

    public static function hsc(string $string) : string
    {
        return htmlspecialchars($string, ENT_NOQUOTES);
    }

    public static function hscq(string $string) : string
    {
        return htmlspecialchars($string, ENT_QUOTES);
    }

    public static function flattenArray(array $demo_array) : array
    {
        $new_array = array();
        array_walk_recursive($demo_array, function($array) use (&$new_array) { $new_array[] = $array; });
        return $new_array;
    }
} 