<?php

use \Necryin\Jax\Application;
use \Necryin\Jax\Request;

require __DIR__ . "/../vendor/autoload.php";
$config = include  __DIR__ . "/../src/config/config.php";

$app = new Application($config);
$request = Request::createFromGlobals();
$response = $app->handleRequest($request);
$response->send();
