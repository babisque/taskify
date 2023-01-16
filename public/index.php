<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use App\Taskify\Controller\Controller;
use App\Taskify\Repository\TaskRepository;
use App\Taskify\Service\ConnectionCreator;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../', '.env');
$dotenv->load();

$routes = require_once __DIR__ . '/../config/Routes.php';

$pdo = ConnectionCreator::createConnection();
$taskRepository = new TaskRepository($pdo);

/** @var Controller $controller */

$pathInfo = $_SERVER['PATH_INFO'];
$httpMethod = $_SERVER['REQUEST_METHOD'];

$key = "$httpMethod|$pathInfo";
if (array_key_exists($key, $routes)) {
    $controllerClass = $routes["$httpMethod|$pathInfo"];
    $controller = new $controllerClass($taskRepository);
} else {
    echo "404";
    exit();
}

$controller->processRequest();