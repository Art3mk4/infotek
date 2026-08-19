<?php

declare(strict_types=1);

namespace App\Domain;

use Yiisoft\ActiveRecord\ActiveRecord;
use Yiisoft\ActiveRecord\Trait\MagicPropertiesTrait;
use Yiisoft\ActiveRecord\Trait\MagicRelationsTrait;
use Yiisoft\ActiveRecord\Trait\RepositoryTrait;

/**
 * User model.
 *
 * @property int|null $id
 * @property string $username
 * @property string $email
 * @property string $password_hash
 * @property string|null $auth_key
 * @property int $status
 * @property \DateTimeImmutable|null $created_at
 * @property \DateTimeImmutable|null $updated_at
 */
final class User extends ActiveRecord
{
    use MagicPropertiesTrait;
    use MagicRelationsTrait;
    use RepositoryTrait;

    public function tableName(): string
    {
        return 'user';
    }

    public function validatePassword(string $password): bool
    {
        return password_verify($password, $this->password_hash);
    }
}
