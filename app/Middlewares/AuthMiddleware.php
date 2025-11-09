<?php

namespace App\Middlewares;

use App\Core\Response;

class AuthMiddleware
{
    public static function handle(callable $next)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (empty($_SESSION['uid'])) {
            Response::redirect('/login');
        }
        return $next();
    }
}
