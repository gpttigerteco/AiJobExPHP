<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Models\Repositories\DocumentRepository;
use App\Models\Repositories\FaqRepository;
use App\Models\Repositories\JobDescriptionRepository;

class DashboardController
{
    public function __construct(
        private AuthService $auth = new AuthService(),
        private JobDescriptionRepository $jobDescriptions = new JobDescriptionRepository(),
        private DocumentRepository $documents = new DocumentRepository(),
        private FaqRepository $faqs = new FaqRepository()
    ) {
    }

    public function index(): void
    {
        $user = $this->auth->currentUser();
        $userId = $user['id_uuid'] ?? null;
        $departmentId = null; // قابل جایگزینی با واحد اصلی کاربر
        $jobDescription = $userId ? $this->jobDescriptions->latestForUser($userId) : null;
        $docs = $departmentId ? $this->documents->latestForDepartment($departmentId) : [];
        $faqs = $this->faqs->popularForDepartment($departmentId);

        view('dashboard/index', [
            'title' => 'داشبورد',
            'user' => $user,
            'jobDescription' => $jobDescription,
            'documents' => $docs,
            'faqs' => $faqs,
        ]);
    }
}
