<?php

namespace App\Models\Repositories;

class AiSessionRepository extends BaseRepository
{
    public function create(string $userId, ?string $contextHint = null): string
    {
        $uuid = $this->generateUuid();
        $stmt = $this->pdo->prepare('INSERT INTO ai_sessions (id, user_id, context_hint) VALUES (UNHEX(REPLACE(:id,"-","")), UNHEX(REPLACE(:user,"-","")), :context)');
        $stmt->execute([
            'id' => $uuid,
            'user' => $userId,
            'context' => $contextHint,
        ]);
        return $uuid;
    }

    public function createMessage(string $sessionId, string $sender, string $text, int $tokensIn = 0, int $tokensOut = 0, float $cost = 0.0): void
    {
        $stmt = $this->pdo->prepare('INSERT INTO ai_messages (id, session_id, sender, text, tokens_in, tokens_out, cost_usd) VALUES (UNHEX(REPLACE(:id,"-","")), UNHEX(REPLACE(:session,"-","")), :sender, :text, :tokensIn, :tokensOut, :cost)');
        $stmt->execute([
            'id' => $this->generateUuid(),
            'session' => $sessionId,
            'sender' => $sender,
            'text' => $text,
            'tokensIn' => $tokensIn,
            'tokensOut' => $tokensOut,
            'cost' => $cost,
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
