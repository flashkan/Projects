<?php

namespace App\server\controllers;

use App\server\models\Actions;
use App\server\models\Asset;
use App\server\models\Operations;

class ActionController
{
    public function index()
    {
        $data = Actions::select();
        foreach ($data as $key => &$elem) {
            $elem['date'] = date('d.m.Y H:i', strtotime($elem['date']));
        }
        echo $GLOBALS['twig']->render('action/action.html.twig', ['actions' => $data]);
    }

    public function add()
    {
        if (isset($_POST['formAddAction'])) {
            if (!is_string($result = Actions::insert($_POST))) {
                $_SESSION['success'] = 'Successfully added';
                $_SESSION['redirect'] = 1;
            } else {
                $_SESSION['failure'] = $result;
            };
        }
        echo $GLOBALS['twig']->render('action/action-add.html.twig', [
            'assets' => Asset::select('id, name'),
            'operations' => Operations::select('id, name'),
            'success' => $_SESSION['success'],
            'failure' => $_SESSION['failure'],
            'redirect' => $_SESSION['redirect'],
        ]);
    }
}