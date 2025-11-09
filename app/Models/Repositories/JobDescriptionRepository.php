<?php

namespace App\Models\Repositories;

class JobDescriptionRepository extends BaseRepository
{
    public function latestForUser(string $userId): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM job_descriptions WHERE user_id = UNHEX(REPLACE(:id, "-", "")) ORDER BY version DESC LIMIT 1');
        $stmt->execute(['id' => $userId]);
        $item = $stmt->fetch();
        return $item ?: null;
    }

    public function historyForUser(string $userId): array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM job_descriptions WHERE user_id = UNHEX(REPLACE(:id, "-", "")) ORDER BY version DESC');
        $stmt->execute(['id' => $userId]);
        return $stmt->fetchAll();
    }
}
