<?php

namespace App\server\controllers;

use App\server\models\Asset;

class AssetController
{
    public function index()
    {
        $arr = Asset::select();
        echo $GLOBALS['twig']->render('asset/asset.html.twig', [
            'assets' => $arr,
            'success' => $_SESSION['success'],
            'failure' => $_SESSION['failure'],
        ]);
    }

    public function add()
    {
        if (isset($_POST['formAddAsset'])) {
            if (!is_string($result = Asset::insert($_POST))) {
                $_SESSION['success'] = 'Successfully added';
                $_SESSION['redirect'] = 1;
            } else {
                $_SESSION['failure'] = $result;
            };
        };
        echo $GLOBALS['twig']->render('asset/asset-add.html.twig', [
            'success' => $_SESSION['success'],
            'failure' => $_SESSION['failure'],
            'redirect' => $_SESSION['redirect'],
        ]);
    }

    public function delete()
    {
        if (isset($_POST['formDeleteAsset'])) {
            if (!is_string($result = Asset::delete($_POST['formDeleteAsset']))) {
                $_SESSION['success'] = 'Successfully deleted';
//                $_SESSION['redirect'] = 1;

            } else {
                $_SESSION['failure'] = $result;
            }
            header('Location: /?url=asset');
        }
    }
}