<?php

namespace App\Services\Ai;

interface AiProvider
{
    public function ask(string $systemPrompt, string $userPrompt, array $contextChunks, int $maxTokens = 512, float $temperature = 0.2): array;
}
