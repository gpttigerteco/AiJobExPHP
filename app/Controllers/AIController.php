<?php

namespace App\Controllers;

use App\Core\Response;
use App\Services\AiService;
use App\Services\AuthService;

class AIController
{
    public function __construct(private AiService $ai = new AiService(), private AuthService $auth = new AuthService())
    {
    }

    public function chatView(): void
    {
        view('ai/chat', ['title' => 'دستیار هوشمند سازمانی']);
    }

    public function message(): void
    {
        if (!verify_csrf_token($_POST['_token'] ?? null)) {
            Response::json(['message' => 'CSRF token mismatch'], 419);
            return;
        }
        $user = $this->auth->currentUser();
        if (!$user) {
            Response::json(['message' => 'Unauthorized'], 401);
            return;
        }
        $text = trim($_POST['message'] ?? '');
        if ($text === '') {
            Response::json(['message' => 'پیام خالی است'], 422);
            return;
        }
        $result = $this->ai->ask($user['id_uuid'], null, $text);
        Response::json($result);
    }
}
