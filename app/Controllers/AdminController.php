<?php

namespace App\Controllers;

use App\Services\ReportService;

class AdminController
{
    public function __construct(private ReportService $reports = new ReportService())
    {
    }

    public function settings(): void
    {
        view('admin/settings', ['title' => 'تنظیمات سامانه']);
    }

    public function aiSettings(): void
    {
        view('admin/ai-settings', [
            'title' => 'تنظیمات هوش مصنوعی',
            'provider' => env('AI_PROVIDER', 'mock'),
        ]);
    }

    public function reports(): void
    {
        view('reports/index', ['title' => 'گزارش‌های مدیریتی']);
    }
}
