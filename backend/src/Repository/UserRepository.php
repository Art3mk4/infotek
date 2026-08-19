<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\User;
use App\Domain\UserRepositoryInterface;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Query\Query;

/**
 * User repository
 */
final class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private ConnectionInterface $db
    ) {
    }

    public function findByUsername(string $username): ?User
    {
        $row = (new Query($this->db))->from('user')->where(['username' => $username])->one();

        return $row ? User::fromArray($row) : null;
    }

    public function findById(int $id): ?User
    {
        $row = (new Query($this->db))->from('user')->where(['id' => $id])->one();

        return $row ? User::fromArray($row) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $row = (new Query($this->db))->from('user')->where(['email' => $email])->one();

        return $row ? User::fromArray($row) : null;
    }
}
