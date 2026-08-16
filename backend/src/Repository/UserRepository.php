<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\User;
use PDO;

/**
 * User repository
 */
final class UserRepository
{
    public function __construct(
        private PDO $db
    ) {
    }

    public function findByUsername(string $username): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM "user" WHERE username = :username');
        $stmt->execute([':username' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? User::fromArray($row) : null;
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM "user" WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? User::fromArray($row) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM "user" WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? User::fromArray($row) : null;
    }
}
