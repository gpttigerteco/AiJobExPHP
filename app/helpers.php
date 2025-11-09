<?php

use App\Core\Response;

function view(string $template, array $data = [], string $layout = 'layouts/app'): void
{
    $viewFile = __DIR__ . '/Views/' . $template . '.php';
    if (!file_exists($viewFile)) {
        throw new RuntimeException("View {$template} not found");
    }

    extract($data, EXTR_OVERWRITE);
    ob_start();
    include $viewFile;
    $content = ob_get_clean();

    $layoutFile = __DIR__ . '/Views/' . $layout . '.php';
    if (file_exists($layoutFile)) {
        include $layoutFile;
    } else {
        echo $content;
    }
}

function redirect(string $to): void
{
    Response::redirect($to);
}

function csrf_token(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}

function verify_csrf_token(?string $token): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    return isset($_SESSION['_csrf_token']) && hash_equals($_SESSION['_csrf_token'], (string)$token);
}
