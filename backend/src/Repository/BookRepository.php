<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\Author;
use App\Domain\Book;
use App\Domain\BookRepositoryInterface;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Query\Query;

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
        $rows = (new Query($this->db))
            ->from('book')
            ->orderBy(['created_at' => SORT_DESC])
            ->limit($limit)
            ->offset($offset)
            ->all();

        $books = array_map(static fn(array $row) => Book::fromArray($row), $rows);
        $this->loadAuthors($books);

        return $books;
    }

    public function findById(int $id): ?Book
    {
        $row = (new Query($this->db))->from('book')->where(['id' => $id])->one();

        if ($row === null) {
            return null;
        }

        $book = Book::fromArray($row);
        $this->loadAuthors([$book]);

        return $book;
    }

    public function create(Book $book): Book
    {
        $this->db->createCommand()->insert('book', [
            'title' => $book->title,
            'year' => $book->year,
            'description' => $book->description,
            'isbn' => $book->isbn,
            'cover_image' => $book->coverImage,
        ])->execute();

        $book->id = (int)$this->db->getLastInsertID('book_id_seq');

        return $book;
    }

    public function update(Book $book): bool
    {
        $affected = $this->db->createCommand()->update(
            'book',
            [
                'title' => $book->title,
                'year' => $book->year,
                'description' => $book->description,
                'isbn' => $book->isbn,
                'cover_image' => $book->coverImage,
            ],
            ['id' => $book->id],
        )->execute();

        return $affected > 0;
    }

    public function delete(int $id): bool
    {
        return $this->db->createCommand()->delete('book', ['id' => $id])->execute() > 0;
    }

    public function linkAuthors(int $bookId, array $authorIds): void
    {
        $this->db->createCommand()->delete('book_author', ['book_id' => $bookId])->execute();

        if (empty($authorIds)) {
            return;
        }

        $this->db->createCommand()->batchInsert(
            'book_author',
            ['book_id', 'author_id'],
            array_map(static fn(int $authorId) => [$bookId, $authorId], $authorIds),
        )->execute();
    }

    public function count(): int
    {
        return (new Query($this->db))->from('book')->count();
    }

    /**
     * Eager-loads authors for a batch of books in a single query, avoiding N+1 selects.
     *
     * @param Book[] $books
     */
    private function loadAuthors(array $books): void
    {
        if (empty($books)) {
            return;
        }

        $booksById = [];
        foreach ($books as $book) {
            $booksById[$book->id] = $book;
        }

        $rows = (new Query($this->db))
            ->select(['a.*', 'ba.book_id'])
            ->from(['a' => 'author'])
            ->innerJoin(['ba' => 'book_author'], 'ba.author_id = a.id')
            ->where(['ba.book_id' => array_keys($booksById)])
            ->all();

        foreach ($rows as $row) {
            $bookId = (int)$row['book_id'];
            unset($row['book_id']);
            $booksById[$bookId]->authors[] = Author::fromArray($row);
        }
    }
}
