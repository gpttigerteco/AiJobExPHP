<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Models\Repositories\JobDescriptionRepository;

class JDController
{
    public function __construct(
        private AuthService $auth = new AuthService(),
        private JobDescriptionRepository $jobDescriptions = new JobDescriptionRepository()
    ) {
    }

    public function my(): void
    {
        $user = $this->auth->currentUser();
        if (!$user) {
            redirect('/login');
        }
        $userId = $user['id_uuid'] ?? null;
        $jd = $userId ? $this->jobDescriptions->latestForUser($userId) : null;
        $history = $userId ? $this->jobDescriptions->historyForUser($userId) : [];
        view('profile/jd', [
            'title' => 'شرح وظایف من',
            'user' => $user,
            'jobDescription' => $jd,
            'history' => $history,
        ]);
    }

    public function suggestChange(): void
    {
        if (!verify_csrf_token($_POST['_token'] ?? null)) {
            http_response_code(419);
            echo 'توکن نامعتبر است';
            return;
        }
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION['flash'][] = ['type' => 'success', 'message' => 'پیشنهاد شما ثبت شد.'];
        redirect('/me/jd');
    }
}
