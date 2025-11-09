<?php

namespace App\Middlewares;

class CsrfMiddleware
{
    public static function handle(callable $next)
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if (in_array($method, ['POST', 'PUT', 'DELETE'], true)) {
            $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            if (!verify_csrf_token($token)) {
                http_response_code(419);
                echo 'CSRF token mismatch';
                return;
            }
        }
        return $next();
    }
}
