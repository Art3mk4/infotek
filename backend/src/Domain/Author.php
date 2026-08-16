<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Author domain model
 */
final class Author
{
    public function __construct(
        public ?int $id = null,
        public string $fullName = '',
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            fullName: $data['full_name'] ?? '',
        );
    }
}
