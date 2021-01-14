<?php


namespace App\server\services;


class Config
{
    public function __construct()
    {
    }

    private static $urlConfig = __DIR__ . '/../../configs/';

    public static function get($forElement)
    {
        $url = self::$urlConfig;
        $fullUrl = $url . $forElement . '.json';
        if (file_exists($fullUrl)) {
            return json_decode(file_get_contents($fullUrl), true);
        }
        var_dump("File '$forElement' not existed");
        return false;
    }
}