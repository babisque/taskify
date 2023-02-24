<?php

declare(strict_types=1);

namespace App\Taskify\Service;

use PDO;
use PDOException;

class ConnectionCreator
{
    public static function createConnection(): PDO
    {
        try {
            $connection = new PDO("pgsql:host={$_ENV['SERVER_ADDRESS']};dbname={$_ENV['DB_NAME']};user={$_ENV['DB_USER']};password={$_ENV['DB_PASSWORD']}");
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            http_response_code(500);
            print_r(json_encode($e->getMessage()));
            exit();
        }

        return $connection;
    }
}