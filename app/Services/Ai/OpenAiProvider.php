<?php

namespace App\Services\Ai;

use RuntimeException;

class OpenAiProvider implements AiProvider
{
    public function __construct(private ?string $apiKey = null)
    {
        $this->apiKey ??= env('OPENAI_API_KEY');
    }

    public function ask(string $systemPrompt, string $userPrompt, array $contextChunks, int $maxTokens = 512, float $temperature = 0.2): array
    {
        if (!$this->apiKey) {
            throw new RuntimeException('OpenAI API key not configured');
        }

        $payload = [
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt . '\n\n' . json_encode($contextChunks, JSON_UNESCAPED_UNICODE)],
            ],
            'max_tokens' => $maxTokens,
            'temperature' => $temperature,
        ];

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->apiKey,
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
        ]);

        $response = curl_exec($ch);
        if ($response === false) {
            throw new RuntimeException('OpenAI request failed: ' . curl_error($ch));
        }
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);
        if ($status >= 400) {
            throw new RuntimeException($data['error']['message'] ?? 'OpenAI error');
        }

        $choice = $data['choices'][0]['message']['content'] ?? '';
        return [
            'answer' => $choice,
            'citations' => [],
            'inputTokens' => $data['usage']['prompt_tokens'] ?? 0,
            'outputTokens' => $data['usage']['completion_tokens'] ?? 0,
            'cost' => 0.0,
        ];
    }
}
