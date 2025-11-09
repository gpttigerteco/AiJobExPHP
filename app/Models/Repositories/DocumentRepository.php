<?php

namespace App\Models\Repositories;

use PDO;

class DocumentRepository extends BaseRepository
{
    public function latestForDepartment(string $departmentId, int $limit = 6): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM documents WHERE department_id = UNHEX(REPLACE(:id, "-", "")) AND is_active = 1 ORDER BY created_at DESC LIMIT :limit');
        $stmt->bindValue(':id', $departmentId);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
