<?php
// routes.php

$router = new Router();

$router->get('/', 'controllers/index.php');
$router->get('/clients', 'controllers/clients.php');
$router->get('/login', 'controllers/login.php');

// Add more routes here as the application is migrated.
