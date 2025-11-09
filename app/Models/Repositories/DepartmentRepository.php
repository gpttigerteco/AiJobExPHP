<?php

namespace App\Models\Repositories;

class DepartmentRepository extends BaseRepository
{
    public function allActive(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM departments WHERE is_active = 1 ORDER BY name');
        return $stmt->fetchAll();
    }
}
