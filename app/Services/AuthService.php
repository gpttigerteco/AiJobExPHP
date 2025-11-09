<?php

namespace App\Services;

use App\Core\Validator;
use App\Models\Repositories\UserRepository;

class AuthService
{
    public function __construct(private UserRepository $users = new UserRepository())
    {
    }

    public function attemptLogin(string $email, string $password): array
    {
        $validator = (new Validator())
            ->required('email', $email, 'ایمیل الزامی است')
            ->email('email', $email, 'ایمیل معتبر نیست')
            ->required('password', $password, 'رمز عبور الزامی است');

        if ($validator->hasErrors()) {
            return ['success' => false, 'errors' => $validator->errors()];
        }

        $user = $this->users->findByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'errors' => ['credentials' => ['ایمیل یا رمز عبور اشتباه است']]];
        }
        if ((int)$user['is_active'] !== 1) {
            return ['success' => false, 'errors' => ['inactive' => ['حساب کاربری غیرفعال است']]];
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION['uid'] = $user['id_uuid'];
        $_SESSION['role'] = $user['role'];

        return ['success' => true, 'user' => $user];
    }

    public function logout(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();
    }

    public function currentUser(): ?array
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $id = $_SESSION['uid'] ?? null;
        if (!$id) {
            return null;
        }
        return $this->users->findById($id);
    }
}
