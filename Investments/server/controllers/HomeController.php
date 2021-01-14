<?php

namespace App\server\controllers;

class HomeController
{
    public static function index()
    {
        echo $GLOBALS['twig']->render('home.html.twig');
    }
}