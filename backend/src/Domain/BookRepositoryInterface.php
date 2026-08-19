<?php

declare(strict_types=1);

namespace App\Domain;

interface BookRepositoryInterface
{
    /** @return Book[] */
    public function findAll(int $limit = 20, int $offset = 0): array;

    public function findById(int $id): ?Book;

    public function create(Book $book): Book;

    public function update(Book $book): bool;

    public function delete(int $id): bool;

    /** @param int[] $authorIds */
    public function linkAuthors(int $bookId, array $authorIds): void;

    public function count(): int;
}
