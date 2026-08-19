<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\Book;
use App\Domain\BookRepositoryInterface;
use Yiisoft\Db\Connection\ConnectionInterface;

/**
 * Book repository
 */
final class BookRepository implements BookRepositoryInterface
{
    public function __construct(
        private ConnectionInterface $db
    ) {
    }

    public function findAll(int $limit = 20, int $offset = 0): array
    {
        return Book::find()
            ->orderBy(['created_at' => SORT_DESC])
            ->limit($limit)
            ->offset($offset)
            ->with('authors')
            ->all();
    }

    public function findById(int $id): ?Book
    {
        return Book::find()->where(['id' => $id])->with('authors')->one();
    }

    public function create(Book $book): Book
    {
        $book->insert();

        return $book;
    }

    public function update(Book $book): bool
    {
        return $book->update() > 0;
    }

    public function delete(int $id): bool
    {
        return (new Book())->deleteAll(['id' => $id]) > 0;
    }

    public function linkAuthors(int $bookId, array $authorIds): void
    {
        $this->db->createCommand()->delete('book_author', ['book_id' => $bookId])->execute();

        if (empty($authorIds)) {
            return;
        }

        $this->db->createCommand()->insertBatch(
            'book_author',
            array_map(static fn(int $authorId) => ['book_id' => $bookId, 'author_id' => $authorId], $authorIds),
        )->execute();
    }

    public function count(): int
    {
        return (int)Book::find()->count();
    }
}
