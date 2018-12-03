<?php

$uri = trim($_SERVER['REQUEST_URI'],'/');

header('Location: events.php');
require 'core/bootstrap.php';

$router = new Router;
require 'routes.php';

// $uri = trim($_SERVER['REQUEST_URI'],'/');

// if ( $uri == '' ) {
// 	header('Location: events.view.php');
// };

//require $router->direct($uri);