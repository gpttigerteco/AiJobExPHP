<?php

namespace App\Controllers;

use App\Models\Repositories\UserRepository;

class UserController
{
    public function __construct(private UserRepository $users = new UserRepository())
    {
    }

    public function index(): void
    {
        $page = (int)($_GET['page'] ?? 1);
        $result = $this->users->paginate($page, 15);
        view('admin/users', [
            'title' => 'کاربران سامانه',
            'users' => $result['data'],
            'total' => $result['total'],
            'page' => $page,
        ]);
    }
}
