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

    public static function transformDataRows(array $input, array $rules) : array
    {
        $output = [];
        
        if ($input)
            foreach ($input as $row)
            {
                $newRow = [];
                foreach ($rules as $newKeyName => $ruleFunction)
                    $newRow[$newKeyName] = $ruleFunction($row);

                $output[] = $newRow;
            }
            
        return $output;
    }
} 