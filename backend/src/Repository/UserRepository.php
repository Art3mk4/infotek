<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\User;
use App\Domain\UserRepositoryInterface;

/**
 * User repository
 */
final class UserRepository implements UserRepositoryInterface
{
    public function findByUsername(string $username): ?User
    {
        return User::findOne(['username' => $username]);
    }

    public function findById(int $id): ?User
    {
        return User::findByPk($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::findOne(['email' => $email]);
    }
}
