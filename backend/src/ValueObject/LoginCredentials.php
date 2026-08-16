<?php

declare(strict_types=1);

namespace App\ValueObject;

/**
 * Immutable data object for login credentials.
 */
final readonly class LoginCredentials
{
    public function __construct(
        public string $username,
        public string $password,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            username: $data['username'] ?? '',
            password: $data['password'] ?? '',
        );
    }
}
