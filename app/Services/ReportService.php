<?php

namespace App\Services;

use App\Core\DB;
use PDO;

class ReportService
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = DB::pdo();
    }

    public function aiCostByDepartment(string $from, string $to): array
    {
        $stmt = $this->pdo->prepare('SELECT d.name, SUM(m.cost_usd) as total_cost FROM ai_messages m JOIN ai_sessions s ON m.session_id = s.id JOIN users u ON s.user_id = u.id LEFT JOIN user_departments ud ON u.id = ud.user_id LEFT JOIN departments d ON ud.dept_id = d.id WHERE m.sender = "AI" AND m.created_at BETWEEN :from AND :to GROUP BY d.name');
        $stmt->execute(['from' => $from, 'to' => $to]);
        return $stmt->fetchAll();
    }

    public function exportCsv(array $rows, array $headers): string
    {
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, $headers);
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        return $csv ?: '';
    }
}
