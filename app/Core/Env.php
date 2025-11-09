<?php

namespace App\Core;

use Dotenv\Dotenv;

class Env
{
    public static function load(string $path): void
    {
        if (!file_exists($path)) {
            return;
        }
        if (is_dir($path)) {
            Dotenv::createImmutable($path)->safeLoad();
        } else {
            $dir = dirname($path);
            $name = basename($path);
            Dotenv::createImmutable($dir, $name)->safeLoad();
        }
    }
}

if (!function_exists('env')) {
    function env(string $key, $default = null)
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? null;
        if ($value === null) {
            return $default;
        }
        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }
        if (is_string($value) && str_starts_with($value, 'env(')) {
            return $default;
        }
        return $value;
    }
}
