<?php

namespace App\server;

use App\server\services\Router;

class App
{
    public function run()
    {
        $GLOBALS['twig'] = $this->twigInit();
        (new Router())->run();
    }

    private function twigInit()
    {
        $loader = new \Twig\Loader\FilesystemLoader(SERVER_URL . 'views');
        return new \Twig\Environment($loader);
    }
}

