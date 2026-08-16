<?php

declare(strict_types=1);

namespace App\ValueObject;

/**
 * Immutable data object for creating a subscription.
 */
final readonly class CreateSubscriptionData
{
    public function __construct(
        public int $authorId,
        public string $phone,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            authorId: (int)($data['author_id'] ?? 0),
            phone: $data['phone'] ?? '',
        );
    }
}
