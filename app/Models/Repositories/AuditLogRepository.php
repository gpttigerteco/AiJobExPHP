<?php

namespace App\Models\Repositories;

class AuditLogRepository extends BaseRepository
{
    public function log(?string $userId, string $action, string $entity, ?string $entityId, array $before = null, array $after = null, ?string $ip = null): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO audit_logs (id, user_id, action, entity, entity_id, before_json, after_json, ip) VALUES (UNHEX(REPLACE(:id,"-","")), IF(:userId IS NULL, NULL, UNHEX(REPLACE(:userId,"-",""))), :action, :entity, IF(:entityId IS NULL, NULL, UNHEX(REPLACE(:entityId,"-",""))), :before, :after, :ip)');
        $stmt->execute([
            'id' => $this->generateUuid(),
            'userId' => $userId,
            'action' => $action,
            'entity' => $entity,
            'entityId' => $entityId,
            'before' => $before ? json_encode($before, JSON_UNESCAPED_UNICODE) : null,
            'after' => $after ? json_encode($after, JSON_UNESCAPED_UNICODE) : null,
            'ip' => $ip,
        ]);
    }

    private function generateUuid(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
