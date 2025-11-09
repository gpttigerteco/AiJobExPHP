<?php

namespace App\Models\Repositories;

use PDO;

class FaqRepository extends BaseRepository
{
    public function popularForDepartment(?string $departmentId, int $limit = 5): array
    {
        if ($departmentId) {
            $stmt = $this->pdo->prepare('SELECT * FROM faqs WHERE department_id = UNHEX(REPLACE(:id, "-", "")) ORDER BY popularity DESC LIMIT :limit');
            $stmt->bindValue(':id', $departmentId);
        } else {
            $stmt = $this->pdo->prepare('SELECT * FROM faqs ORDER BY popularity DESC LIMIT :limit');
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
