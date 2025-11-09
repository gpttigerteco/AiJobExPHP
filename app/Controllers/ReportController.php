<?php

namespace App\Controllers;

use App\Core\Response;
use App\Services\ReportService;

class ReportController
{
    public function __construct(private ReportService $reports = new ReportService())
    {
    }

    public function index(): void
    {
        view('reports/index', ['title' => 'گزارش‌ها']);
    }

    public function export(): void
    {
        $from = $_GET['from'] ?? date('Y-m-01');
        $to = $_GET['to'] ?? date('Y-m-d');
        $data = $this->reports->aiCostByDepartment($from, $to);
        $csv = $this->reports->exportCsv($data, ['department', 'cost']);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="ai-cost-report.csv"');
        echo $csv;
    }
}
