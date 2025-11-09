<?php

namespace App\Services;

use App\Models\Repositories\DocumentRepository;
use App\Models\Repositories\FaqRepository;
use App\Models\Repositories\JobDescriptionRepository;

class RAGService
{
    public function __construct(
        private JobDescriptionRepository $jobDescriptions = new JobDescriptionRepository(),
        private DocumentRepository $documents = new DocumentRepository(),
        private FaqRepository $faqs = new FaqRepository()
    ) {
    }

    public function gatherContext(?string $userId, ?string $departmentId): array
    {
        $context = [];
        if ($userId) {
            $jd = $this->jobDescriptions->latestForUser($userId);
            if ($jd) {
                $context[] = [
                    'type' => 'JobDescription',
                    'id' => $this->toUuid($jd['id'] ?? null),
                    'title' => $jd['title'],
                    'body' => $jd['body_md'],
                ];
            }
        }
        if ($departmentId) {
            foreach ($this->documents->latestForDepartment($departmentId) as $doc) {
                $context[] = [
                    'type' => 'Document',
                    'id' => $this->toUuid($doc['id'] ?? null),
                    'title' => $doc['title'],
                    'body' => $doc['tags'],
                ];
            }
            foreach ($this->faqs->popularForDepartment($departmentId) as $faq) {
                $context[] = [
                    'type' => 'Faq',
                    'id' => $this->toUuid($faq['id'] ?? null),
                    'title' => $faq['question'],
                    'body' => $faq['answer'],
                ];
            }
        }
        return $context;
    }

    private function toUuid(?string $binary): ?string
    {
        if (!$binary) {
            return null;
        }
        $hex = bin2hex($binary);
        return sprintf('%s-%s-%s-%s-%s',
            substr($hex, 0, 8),
            substr($hex, 8, 4),
            substr($hex, 12, 4),
            substr($hex, 16, 4),
            substr($hex, 20)
        );
    }
}
