<?php

namespace App\server\services;

class UploadedFile
{
    public static function saveAssetImg($file)
    {
        $shortFileName = uniqid() . '_' . basename($file['name']);
        $typeFile = $file['type'];
        if ($typeFile === 'image/png' || $typeFile === 'image/jpg' || $typeFile === 'image/jpeg') {
            $urlImage = ROOT_URL . '/public/img/assets/' . $shortFileName;
            if (move_uploaded_file($file['tmp_name'], $urlImage)) {
                return $shortFileName;
            }
            var_dump('error: file is not uploaded');
        }
    }
}