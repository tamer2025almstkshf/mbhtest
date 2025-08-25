<?php
// --- APPLICATION BOOTSTRAP ---
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Database.php';
require_once __DIR__ . '/src/view.php';
require_once __DIR__ . '/src/Router.php';

// --- ROUTING ---
// Parse the requested URI.
$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

// Load the routes and direct the request.
require 'routes.php';
$controllerPath = $router->direct($uri, $method);

// --- LOAD CONTROLLER ---
// All controller files will be included here.
// The controller will then be responsible for its own logic,
// including calling the view renderer.
require $controllerPath;
