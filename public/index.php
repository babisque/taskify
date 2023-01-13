<?php

use App\Taskify\Service\ConnectionCreator;
use Dotenv\Dotenv;

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/../.env');
$dotenv->load();

$pdo = ConnectionCreator::createConnection();
