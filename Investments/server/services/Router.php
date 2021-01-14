<?php

namespace App\server\services;

use App\server\services\Config;

class Router
{
    private $defaultRoute = 'App\server\controllers\HomeController::index';
    private $routes = [];
    private $controller;
    private $action;

    public function __construct()
    {
        $this->routes = Config::get('routes');
    }

    public function run()
    {
        $this->pageRouting();
    }

    private function pageRouting()
    {
        if ($url = $_GET['url']) {
            $arr = explode('/', $url);

            $this->checkController($arr);

            if ($this->controller) {
                $this->checkAction($arr);
            }
        }

        $result = '';
        if (($c = $this->controller) && ($a = $this->action)) $result = $c . "::$a";
        elseif ($c = $this->controller) $result = $c . '::index';
        else $result = $this->defaultRoute;

        $result();
    }

    private function checkController(array $arr)
    {
        foreach ($this->routes as $controllers => $actions) {
            if ($controllers === strtolower($arr[0])) {
                $this->controller = 'App\server\controllers\\' . ucfirst($arr[0]) . 'Controller';
                return;
            }
        }
        return;
    }

    private function checkAction(array $arr)
    {
        if ($urlAction = $arr[1]) {
            foreach ($this->routes[$arr[0]] as $action) {
                if ($action === strtolower($urlAction)) {
                    $this->action = $urlAction;
                    return;
                }
            }
            return;
        }
    }

    /**
     * Функция для всех GET запросов.
     */
    private function getRequests()
    {

    }

    /**
     * Функция для всех POST запросов.
     */
    private function postRequests()
    {
//        var_dump($_FILES);
        if (isset($_POST['formAddAsset'])) $this->postAddAsset();
    }


}