<?php

declare(strict_types=1);

namespace App\Domain;

use Yiisoft\ActiveRecord\ActiveQuery;
use Yiisoft\ActiveRecord\ActiveRecord;
use Yiisoft\ActiveRecord\Trait\MagicPropertiesTrait;
use Yiisoft\ActiveRecord\Trait\MagicRelationsTrait;
use Yiisoft\ActiveRecord\Trait\RepositoryTrait;

/**
 * Subscription model.
 *
 * @property int|null $id
 * @property int $author_id
 * @property string $phone
 * @property \DateTimeImmutable|null $created_at
 * @property-read Author|null $author
 */
final class Subscription extends ActiveRecord
{
    use MagicPropertiesTrait;
    use MagicRelationsTrait;
    use RepositoryTrait;

    public function tableName(): string
    {
        return 'subscription';
    }

    public function getAuthorQuery(): ActiveQuery
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}
