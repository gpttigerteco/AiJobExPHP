<?php

declare(strict_types=1);

use App\Controllers\AdminController;
use App\Controllers\AIController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\DeptController;
use App\Controllers\DocController;
use App\Controllers\JDController;
use App\Controllers\ReportController;
use App\Controllers\UserController;
use App\Core\Env;
use App\Core\Router;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\CsrfMiddleware;
use App\Middlewares\RoleMiddleware;

require __DIR__ . '/../vendor/autoload.php';

Env::load(__DIR__ . '/../.env');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

date_default_timezone_set(config('app.timezone', 'Asia/Tehran'));

$router = new Router();

$router->use('csrf', [CsrfMiddleware::class, 'handle']);
$router->use('auth', [AuthMiddleware::class, 'handle']);
$router->use('role.admin', RoleMiddleware::allow(['Admin']));
$router->use('role.hr', RoleMiddleware::allow(['Admin', 'HR']));

$router->get('/', ['uses' => [DashboardController::class, 'index'], 'middleware' => ['auth']]);

$router->get('/login', ['uses' => [AuthController::class, 'loginView']]);
$router->post('/login', ['uses' => [AuthController::class, 'login'], 'middleware' => ['csrf']]);
$router->post('/logout', ['uses' => [AuthController::class, 'logout'], 'middleware' => ['csrf', 'auth']]);

$router->get('/me/jd', ['uses' => [JDController::class, 'my'], 'middleware' => ['auth']]);
$router->post('/me/jd/suggest-change', ['uses' => [JDController::class, 'suggestChange'], 'middleware' => ['auth', 'csrf']]);
$router->get('/me/docs', ['uses' => [DocController::class, 'my'], 'middleware' => ['auth']]);

$router->get('/admin/users', ['uses' => [UserController::class, 'index'], 'middleware' => ['auth', 'role.admin']]);
$router->get('/admin/departments', ['uses' => [DeptController::class, 'index'], 'middleware' => ['auth', 'role.admin']]);
$router->get('/admin/documents', ['uses' => [DocController::class, 'index'], 'middleware' => ['auth', 'role.hr']]);
$router->post('/admin/documents/upload', ['uses' => [DocController::class, 'upload'], 'middleware' => ['auth', 'role.hr', 'csrf']]);
$router->get('/admin/settings', ['uses' => [AdminController::class, 'settings'], 'middleware' => ['auth', 'role.admin']]);
$router->get('/admin/settings/ai', ['uses' => [AdminController::class, 'aiSettings'], 'middleware' => ['auth', 'role.admin']]);

$router->get('/ai/chat', ['uses' => [AIController::class, 'chatView'], 'middleware' => ['auth']]);
$router->post('/api/ai/message', ['uses' => [AIController::class, 'message'], 'middleware' => ['auth', 'csrf']]);

$router->get('/reports', ['uses' => [ReportController::class, 'index'], 'middleware' => ['auth', 'role.admin']]);
$router->get('/reports/export', ['uses' => [ReportController::class, 'export'], 'middleware' => ['auth', 'role.admin']]);

$router->dispatch();

function config(string $key, $default = null)
{
    static $config = [];
    if (!$config) {
        $config['app'] = require __DIR__ . '/../config/app.php';
    }
    $segments = explode('.', $key);
    $value = $config[array_shift($segments)] ?? null;
    foreach ($segments as $segment) {
        if (is_array($value) && array_key_exists($segment, $value)) {
            $value = $value[$segment];
        } else {
            return $default;
        }
    }
    return $value ?? $default;
}
