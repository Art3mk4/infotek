<?php

declare(strict_types=1);

namespace App\ValueObject;

/**
 * Immutable data object for updating a book.
 */
final readonly class UpdateBookData
{
    /**
     * @param int[]|null $authorIds
     */
    public function __construct(
        public ?string $title,
        public ?int $year,
        public ?string $description,
        public ?string $isbn,
        public ?string $coverImage,
        public ?array $authorIds,
    ) {
    }

    public static function fromArray(array $data): self
    {
        $authorIds = $data['author_ids'] ?? null;
        if ($authorIds !== null && !is_array($authorIds)) {
            $authorIds = [];
        }

        return new self(
            title: isset($data['title']) ? trim($data['title']) : null,
            year: isset($data['year']) ? (int)$data['year'] : null,
            description: $data['description'] ?? null,
            isbn: $data['isbn'] ?? null,
            coverImage: $data['cover_image'] ?? null,
            authorIds: $authorIds !== null ? array_map('intval', $authorIds) : null,
        );
    }
}
