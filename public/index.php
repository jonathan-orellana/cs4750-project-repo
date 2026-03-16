<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/Database.php';

session_start();

require_once __DIR__ . '/../app/utilities/Request.php';
require_once __DIR__ . '/../app/utilities/Response.php';
require_once __DIR__ . '/../app/utilities/Router.php';
require_once __DIR__ . '/../app/utilities/Session.php';

require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';

$router = new Router();

require_once __DIR__ . '/../routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($method, $uri);