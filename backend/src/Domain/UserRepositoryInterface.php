<?php

declare(strict_types=1);

namespace App\Domain;

interface UserRepositoryInterface
{
    public function findByUsername(string $username): ?User;

    public function findById(int $id): ?User;

    public function findByEmail(string $email): ?User;
}
