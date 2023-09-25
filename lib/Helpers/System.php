<?php
namespace Congress\Lib\Helpers;

final class System
{
    private function __construct() { }

    public static function systemBaseDir() : string
    {
        return __DIR__ . '/../..';
    } 

    public static function getMailConfigs()
    {
        $configs = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/congress_config.ini", true);
        return $configs['regularmail'];
    }

    public static function eventName() : string
    {
        return "Congresso";
    }
}