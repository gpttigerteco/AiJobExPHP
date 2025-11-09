<?php

namespace App\Services;

use RuntimeException;

class FileService
{
    private array $allowedMime = [
        'application/pdf',
        'text/markdown',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];

    public function store(array $file): string
    {
        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            throw new RuntimeException('خطا در بارگذاری فایل');
        }
        if (!in_array($file['type'], $this->allowedMime, true)) {
            throw new RuntimeException('نوع فایل مجاز نیست');
        }
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = bin2hex(random_bytes(16)) . ($ext ? '.' . $ext : '');
        $destinationDir = rtrim(env('UPLOAD_DIR'), '/');
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0775, true);
        }
        $destination = $destinationDir . '/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new RuntimeException('انتقال فایل ناموفق بود');
        }
        return $filename;
    }
}
