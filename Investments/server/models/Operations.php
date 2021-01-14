<?php

namespace App\server\models;

use App\server\services\DB;

class Operations
{
    private static $table = 'operations';

    public static function select($fields = '*')
    {
        return DB::select(self::$table, $fields);
    }
}