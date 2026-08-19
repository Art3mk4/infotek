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
        /** @var Book[] */
        public array $books = [],
        /** @var Subscription[] */
        public array $subscriptions = [],
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
