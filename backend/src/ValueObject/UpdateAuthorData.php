<?php

declare(strict_types=1);

namespace App\ValueObject;

/**
 * Immutable data object for updating an author.
 */
final readonly class UpdateAuthorData
{
    public function __construct(
        public string $fullName,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            fullName: trim($data['full_name'] ?? ''),
        );
    }
}
