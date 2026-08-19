<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\Author;
use App\Domain\AuthorRepositoryInterface;
use App\Domain\Book;
use App\Domain\Subscription;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Query\Query;

/**
 * Author repository
 */
final class AuthorRepository implements AuthorRepositoryInterface
{
    public function __construct(
        private ConnectionInterface $db
    ) {
    }

    public function findAll(int $limit = 50, int $offset = 0): array
    {
        $rows = (new Query($this->db))
            ->from('author')
            ->orderBy(['full_name' => SORT_ASC])
            ->limit($limit)
            ->offset($offset)
            ->all();

        return array_map(static fn(array $row) => Author::fromArray($row), $rows);
    }

    public function findById(int $id): ?Author
    {
        $row = (new Query($this->db))->from('author')->where(['id' => $id])->one();

        return $row ? Author::fromArray($row) : null;
    }

    public function findByIdWithRelations(int $id): ?Author
    {
        $author = $this->findById($id);
        if ($author === null) {
            return null;
        }

        $this->loadBooks([$author]);
        $this->loadSubscriptions([$author]);

        return $author;
    }

    public function create(Author $author): Author
    {
        $this->db->createCommand()->insert('author', ['full_name' => $author->fullName])->execute();

        $author->id = (int)$this->db->getLastInsertID('author_id_seq');

        return $author;
    }

    public function update(Author $author): bool
    {
        return $this->db->createCommand()
            ->update('author', ['full_name' => $author->fullName], ['id' => $author->id])
            ->execute() > 0;
    }

    public function delete(int $id): bool
    {
        return $this->db->createCommand()->delete('author', ['id' => $id])->execute() > 0;
    }

    public function count(): int
    {
        return (new Query($this->db))->from('author')->count();
    }

    public function top10ByYear(int $year): array
    {
        return (new Query($this->db))
            ->select(['author.id', 'author.full_name', 'COUNT(book.id) AS books_count'])
            ->from('author')
            ->innerJoin('book_author', 'book_author.author_id = author.id')
            ->innerJoin('book', 'book.id = book_author.book_id')
            ->where(['book.year' => $year])
            ->groupBy(['author.id', 'author.full_name'])
            ->orderBy(['books_count' => SORT_DESC])
            ->limit(10)
            ->all();
    }

    /**
     * Eager-loads books for a batch of authors in a single query.
     *
     * @param Author[] $authors
     */
    private function loadBooks(array $authors): void
    {
        if (empty($authors)) {
            return;
        }

        $authorsById = [];
        foreach ($authors as $author) {
            $authorsById[$author->id] = $author;
        }

        $rows = (new Query($this->db))
            ->select(['b.*', 'ba.author_id'])
            ->from(['b' => 'book'])
            ->innerJoin(['ba' => 'book_author'], 'ba.book_id = b.id')
            ->where(['ba.author_id' => array_keys($authorsById)])
            ->all();

        foreach ($rows as $row) {
            $authorId = (int)$row['author_id'];
            unset($row['author_id']);
            $authorsById[$authorId]->books[] = Book::fromArray($row);
        }
    }

    /**
     * Eager-loads subscriptions for a batch of authors in a single query.
     *
     * @param Author[] $authors
     */
    private function loadSubscriptions(array $authors): void
    {
        if (empty($authors)) {
            return;
        }

        $authorsById = [];
        foreach ($authors as $author) {
            $authorsById[$author->id] = $author;
        }

        $rows = (new Query($this->db))
            ->from('subscription')
            ->where(['author_id' => array_keys($authorsById)])
            ->all();

        foreach ($rows as $row) {
            $subscription = Subscription::fromArray($row);
            $authorsById[$subscription->authorId]->subscriptions[] = $subscription;
        }
    }
}
