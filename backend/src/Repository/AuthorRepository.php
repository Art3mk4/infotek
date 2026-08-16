<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\Author;
use PDO;

/**
 * Author repository
 */
final class AuthorRepository
{
    public function __construct(
        private PDO $db
    ) {
    }

    public function findAll(int $limit = 50, int $offset = 0): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM author ORDER BY full_name LIMIT :limit OFFSET :offset'
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return array_map(
            fn($row) => Author::fromArray($row),
            $stmt->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function findById(int $id): ?Author
    {
        $stmt = $this->db->prepare('SELECT * FROM author WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? Author::fromArray($row) : null;
    }

    public function create(Author $author): Author
    {
        $stmt = $this->db->prepare(
            'INSERT INTO author (full_name) VALUES (:full_name)'
        );
        $stmt->execute([':full_name' => $author->fullName]);

        $author->id = (int)$this->db->lastInsertId();
        return $author;
    }

    public function update(Author $author): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE author SET full_name = :full_name WHERE id = :id'
        );
        return $stmt->execute([
            ':id' => $author->id,
            ':full_name' => $author->fullName,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM author WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function count(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM author');
        return (int)$stmt->fetchColumn();
    }

    public function top10ByYear(int $year): array
    {
        $stmt = $this->db->prepare(
            'SELECT
                author.id,
                author.full_name,
                COUNT(book.id) AS books_count
            FROM author
            INNER JOIN book_author ON book_author.author_id = author.id
            INNER JOIN book ON book.id = book_author.book_id
            WHERE book.year = :year
            GROUP BY author.id, author.full_name
            ORDER BY books_count DESC
            LIMIT 10'
        );

        $stmt->execute([':year' => $year]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
