<?php

declare(strict_types=1);

namespace App\Domain;

use Yiisoft\ActiveRecord\ActiveQuery;
use Yiisoft\ActiveRecord\ActiveRecord;
use Yiisoft\ActiveRecord\Trait\MagicPropertiesTrait;
use Yiisoft\ActiveRecord\Trait\MagicRelationsTrait;
use Yiisoft\ActiveRecord\Trait\RepositoryTrait;

/**
 * Author model.
 *
 * @property int|null $id
 * @property string $full_name
 * @property-read Book[] $books
 * @property-read Subscription[] $subscriptions
 */
final class Author extends ActiveRecord
{
    use MagicPropertiesTrait;
    use MagicRelationsTrait;
    use RepositoryTrait;

    public function tableName(): string
    {
        return 'author';
    }

    public function getBooksQuery(): ActiveQuery
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])->viaTable('book_author', ['author_id' => 'id']);
    }

    public function getSubscriptionsQuery(): ActiveQuery
    {
        return $this->hasMany(Subscription::class, ['author_id' => 'id']);
    }
}
