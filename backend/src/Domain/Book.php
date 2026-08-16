<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Book domain model
 */
final class Book
{
    public function __construct(
        public ?int $id = null,
        public string $title = '',
        public int $year = 0,
        public ?string $description = null,
        public ?string $isbn = null,
        public ?string $coverImage = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
        /** @var Author[] */
        public array $authors = [],
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            title: $data['title'] ?? '',
            year: (int)($data['year'] ?? 0),
            description: $data['description'] ?? null,
            isbn: $data['isbn'] ?? null,
            coverImage: $data['cover_image'] ?? null,
            createdAt: $data['created_at'] ?? null,
            updatedAt: $data['updated_at'] ?? null,
        );
    }
}
