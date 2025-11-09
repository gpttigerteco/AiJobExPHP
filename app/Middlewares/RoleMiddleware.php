<?php

namespace App\Middlewares;

use App\Core\Response;

class RoleMiddleware
{
    public static function allow(array $roles): callable
    {
        return function (callable $next) use ($roles) {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            $currentRole = $_SESSION['role'] ?? null;
            if (!$currentRole || !in_array($currentRole, $roles, true)) {
                Response::redirect('/');
            }
            return $next();
        };
    }
}
