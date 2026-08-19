<?php

declare(strict_types=1);

namespace App\Domain;

use Yiisoft\ActiveRecord\ActiveQuery;
use Yiisoft\ActiveRecord\ActiveRecord;
use Yiisoft\ActiveRecord\Trait\MagicPropertiesTrait;
use Yiisoft\ActiveRecord\Trait\MagicRelationsTrait;
use Yiisoft\ActiveRecord\Trait\RepositoryTrait;

/**
 * Book model.
 *
 * @property int|null $id
 * @property string $title
 * @property int $year
 * @property string|null $description
 * @property string|null $isbn
 * @property string|null $cover_image
 * @property \DateTimeImmutable|null $created_at
 * @property \DateTimeImmutable|null $updated_at
 * @property-read Author[] $authors
 */
final class Book extends ActiveRecord
{
    use MagicPropertiesTrait;
    use MagicRelationsTrait;
    use RepositoryTrait;

    public function tableName(): string
    {
        return 'book';
    }

    public function getAuthorsQuery(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])->viaTable('book_author', ['book_id' => 'id']);
    }
}
