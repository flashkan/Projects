<?php

namespace App\server\models;

use App\server\services\DB;
use App\server\services\ParserPage;

class Actions
{
    private static $table = 'actions';

    private static $fields = [
        'asset_id' => [],
        'count' => [],
        'price' => [],
        'commis' => [],
        'currency_id' => [],
        'operation_id' => [],
        'date' => [],
    ];

    public static function select($fields = '*')
    {
        $query = 'SELECT ac.id, ass.id id_asset, ass.name, ass.ticker, ass.url_img, ac.count, ac.price, ac.commis, ac.date, 
            c.name currency, o.name operation FROM actions ac join assets ass join currency c join operations o 
            on ac.asset_id = ass.id and ac.currency_id = c.id and ac.operation_id = o.id order by ac.date DESC;';
        return DB::querySelect($query);
    }

    public static function insert(array $post)
    {
        $arrayKeys = array_keys(self::$fields);
        $arr = array_fill_keys($arrayKeys, '');

        foreach ($arr as $key => &$value) {
            if (array_key_exists($key, $post)) {
                $value = $post[$key];
            }
        }

        if (DB::insert(self::$table, $arr)) return true;
        return 'Not saved to base';
    }
}

