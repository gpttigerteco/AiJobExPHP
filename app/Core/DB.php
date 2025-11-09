<?php

namespace App\Core;

use PDO;
use PDOException;

final class DB
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (!self::$pdo) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                env('DB_HOST'),
                env('DB_PORT'),
                env('DB_DATABASE')
            );
            try {
                self::$pdo = new PDO($dsn, env('DB_USERNAME'), env('DB_PASSWORD'), [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                if (env('APP_DEBUG', false)) {
                    throw $e;
                }
                die('Database connection error.');
            }
        }
        return self::$pdo;
    }
}
