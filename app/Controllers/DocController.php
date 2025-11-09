<?php

namespace App\Controllers;

use App\Services\FileService;

class DocController
{
    public function __construct(private FileService $files = new FileService())
    {
    }

    public function index(): void
    {
        view('admin/documents', ['title' => 'مدیریت مستندات']);
    }

    public function my(): void
    {
        view('profile/documents', ['title' => 'مستندات من']);
    }

    public function upload(): void
    {
        if (!verify_csrf_token($_POST['_token'] ?? null)) {
            http_response_code(419);
            echo 'توکن نامعتبر است';
            return;
        }
        try {
            $filename = $this->files->store($_FILES['file']);
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            $_SESSION['flash'][] = ['type' => 'success', 'message' => 'فایل با موفقیت بارگذاری شد: ' . $filename];
        } catch (\Throwable $e) {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            $_SESSION['flash'][] = ['type' => 'danger', 'message' => $e->getMessage()];
        }
        redirect('/admin/documents');
    }
}
