<?php


namespace App\server\models;


use App\server\services\DB;
use App\server\services\ParserPage;
use App\server\services\UploadedFile;

class Asset
{
    private static $table = 'assets';

    private static $fields = [
        'name' => [],
        'ticker' => [],
        'url_img' => []
    ];

    public static function select($fields = '*')
    {
        return DB::select(self::$table, $fields);
    }

    public static function insert(array $post)
    {
        $result = (new ParserPage())->getAsset($post['ticker'], $post['type']);
        if (!$result) return 'This ticker was not found';

        foreach (Asset::select('ticker') as $key => $value) {
            if (strtoupper($value['ticker']) === strtoupper($result['ticker'])) {
                return "This ticker exists";
            }
        }

        if (DB::insert(self::$table, $result)) return true;
        return 'Not saved to base';
    }

    public static function delete($id)
    {
        if (DB::delete(self::$table, $id)) return true;
        return 'Not saved to base';
    }
}