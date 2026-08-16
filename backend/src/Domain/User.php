<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * User domain model
 */
final class User
{
    public function __construct(
        public ?int $id = null,
        public string $username = '',
        public string $email = '',
        public string $passwordHash = '',
        public ?string $authKey = null,
        public int $status = 10,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {
    }

    public function validatePassword(string $password): bool
    {
        return password_verify($password, $this->passwordHash);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            username: $data['username'] ?? '',
            email: $data['email'] ?? '',
            passwordHash: $data['password_hash'] ?? '',
            authKey: $data['auth_key'] ?? null,
            status: (int)($data['status'] ?? 10),
            createdAt: $data['created_at'] ?? null,
            updatedAt: $data['updated_at'] ?? null,
        );
    }
}
