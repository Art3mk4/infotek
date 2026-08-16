<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\Book;
use App\Domain\Author;
use PDO;

/**
 * Book repository
 */
final class BookRepository
{
    public function __construct(
        private PDO $db
    ) {
    }

    public function findAll(int $limit = 20, int $offset = 0): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM book ORDER BY created_at DESC LIMIT :limit OFFSET :offset'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $books = array_map(
            fn($row) => Book::fromArray($row),
            $stmt->fetchAll(PDO::FETCH_ASSOC)
        );

        // Load authors for each book
        foreach ($books as $book) {
            $book->authors = $this->findAuthorsByBookId($book->id);
        }

        return $books;
    }

    public function findById(int $id): ?Book
    {
        $stmt = $this->db->prepare('SELECT * FROM book WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $book = Book::fromArray($row);
        $book->authors = $this->findAuthorsByBookId($book->id);

        return $book;
    }

    public function create(Book $book): Book
    {
        $stmt = $this->db->prepare(
            'INSERT INTO book (title, year, description, isbn, cover_image)
             VALUES (:title, :year, :description, :isbn, :cover_image)'
        );
        $stmt->execute([
            ':title' => $book->title,
            ':year' => $book->year,
            ':description' => $book->description,
            ':isbn' => $book->isbn,
            ':cover_image' => $book->coverImage,
        ]);

        $book->id = (int)$this->db->lastInsertId();
        return $book;
    }

    public function update(Book $book): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE book SET title = :title, year = :year, description = :description,
             isbn = :isbn, cover_image = :cover_image WHERE id = :id'
        );
        return $stmt->execute([
            ':id' => $book->id,
            ':title' => $book->title,
            ':year' => $book->year,
            ':description' => $book->description,
            ':isbn' => $book->isbn,
            ':cover_image' => $book->coverImage,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM book WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function linkAuthors(int $bookId, array $authorIds): void
    {
        // First, remove existing links
        $stmt = $this->db->prepare('DELETE FROM book_author WHERE book_id = :book_id');
        $stmt->execute([':book_id' => $bookId]);

        // Then add new links
        if (!empty($authorIds)) {
            $stmt = $this->db->prepare(
                'INSERT INTO book_author (book_id, author_id) VALUES (:book_id, :author_id)'
            );
            foreach ($authorIds as $authorId) {
                $stmt->execute([
                    ':book_id' => $bookId,
                    ':author_id' => $authorId,
                ]);
            }
        }
    }

    public function findAuthorsByBookId(int $bookId): array
    {
        $stmt = $this->db->prepare(
            'SELECT a.* FROM author a
             INNER JOIN book_author ba ON ba.author_id = a.id
             WHERE ba.book_id = :book_id'
        );
        $stmt->execute([':book_id' => $bookId]);

        return array_map(
            fn($row) => Author::fromArray($row),
            $stmt->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function count(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM book');
        return (int)$stmt->fetchColumn();
    }
}
