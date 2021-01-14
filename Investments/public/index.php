<?php

define('ROOT_URL', __DIR__ . '/../');
define('PUBLIC_URL', __DIR__);
define('SERVER_URL', __DIR__ . '/../server/');

require ROOT_URL . 'vendor/autoload.php';

$app = new \App\server\App();
$app->run();