<?php

declare(strict_types=1);

namespace App\ValueObject;

/**
 * Immutable data object for creating a book.
 */
final readonly class CreateBookData
{
    /**
     * @param int[] $authorIds
     */
    public function __construct(
        public string $title,
        public int $year,
        public ?string $description,
        public ?string $isbn,
        public ?string $coverImage,
        public array $authorIds,
    ) {
    }

    public static function fromArray(array $data): self
    {
        $authorIds = $data['author_ids'] ?? [];
        if (!is_array($authorIds)) {
            $authorIds = [];
        }

        return new self(
            title: trim($data['title'] ?? ''),
            year: (int)($data['year'] ?? date('Y')),
            description: $data['description'] ?? null,
            isbn: $data['isbn'] ?? null,
            coverImage: $data['cover_image'] ?? null,
            authorIds: array_map('intval', $authorIds),
        );
    }
}
