<?php

namespace App\Controllers;

use App\Services\AuthService;

class AuthController
{
    public function __construct(private AuthService $auth = new AuthService())
    {
    }

    public function loginView(): void
    {
        view('auth/login', ['title' => 'ورود به سامانه'], 'layouts/guest');
    }

    public function login(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $result = $this->auth->attemptLogin($email, $password);
        if (!$result['success']) {
            view('auth/login', [
                'title' => 'ورود به سامانه',
                'errors' => $result['errors'],
            ], 'layouts/guest');
            return;
        }
        redirect('/');
    }

    public function logout(): void
    {
        $this->auth->logout();
        redirect('/login');
    }
}
