<?php

namespace App\Services\Ai;

class MockAiProvider implements AiProvider
{
    public function ask(string $systemPrompt, string $userPrompt, array $contextChunks, int $maxTokens = 512, float $temperature = 0.2): array
    {
        $contextSummary = array_map(fn ($chunk) => $chunk['title'] ?? 'منبع بدون عنوان', $contextChunks);
        $answer = 'پاسخ ش模ک: ' . ($contextSummary ? 'بر اساس ' . implode('، ', $contextSummary) : 'بدون منبع مشخص') . '.\n' . $userPrompt;
        return [
            'answer' => $answer,
            'citations' => array_map(function ($chunk) {
                return [
                    'type' => $chunk['type'] ?? 'Context',
                    'id' => $chunk['id'] ?? null,
                    'title' => $chunk['title'] ?? 'نامشخص',
                ];
            }, $contextChunks),
            'inputTokens' => strlen($userPrompt),
            'outputTokens' => strlen($answer),
            'cost' => 0.0,
        ];
    }
}
