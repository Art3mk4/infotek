<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Subscription domain model
 */
final class Subscription
{
    public function __construct(
        public ?int $id = null,
        public int $authorId = 0,
        public string $phone = '',
        public ?string $createdAt = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            authorId: (int)($data['author_id'] ?? 0),
            phone: $data['phone'] ?? '',
            createdAt: $data['created_at'] ?? null,
        );
    }
}
