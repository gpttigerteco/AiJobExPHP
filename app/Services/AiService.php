<?php

namespace App\Services;

use App\Models\Repositories\AiSessionRepository;
use App\Services\Ai\AiProvider;
use App\Services\Ai\MockAiProvider;

class AiService
{
    public function __construct(
        private AiSessionRepository $sessions = new AiSessionRepository(),
        private RAGService $rag = new RAGService(),
        private ?AiProvider $provider = null
    ) {
        $this->provider ??= $this->resolveProvider();
    }

    public function ask(string $userId, ?string $departmentId, string $message): array
    {
        $context = $this->rag->gatherContext($userId, $departmentId);
        $systemPrompt = 'دستیار داخلی سازمانی. فقط از منابع مجاز استفاده کن و در صورت ابهام کمبود داده را اعلام کن.';
        $response = $this->provider->ask($systemPrompt, $message, $context, 512, 0.2);

        $sessionId = $this->sessions->create($userId);
        $this->sessions->createMessage($sessionId, 'User', $message, strlen($message));
        $this->sessions->createMessage($sessionId, 'AI', $response['answer'], $response['inputTokens'] ?? 0, $response['outputTokens'] ?? 0, $response['cost'] ?? 0);

        return [
            'sessionId' => $sessionId,
            'answer' => $response['answer'],
            'citations' => $response['citations'],
            'usage' => [
                'inputTokens' => $response['inputTokens'],
                'outputTokens' => $response['outputTokens'],
                'cost' => $response['cost'],
            ],
        ];
    }

    private function resolveProvider(): AiProvider
    {
        return match (env('AI_PROVIDER', 'mock')) {
            'openai' => new \App\Services\Ai\OpenAiProvider(),
            default => new MockAiProvider(),
        };
    }
}
