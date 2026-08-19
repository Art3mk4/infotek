<?php

declare(strict_types=1);

namespace App\Domain;

interface AuthorRepositoryInterface
{
    /** @return Author[] */
    public function findAll(int $limit = 50, int $offset = 0): array;

    public function findById(int $id): ?Author;

    /**
     * Same as {@see findById()}, but also eager-loads the author's books and subscriptions.
     */
    public function findByIdWithRelations(int $id): ?Author;

    public function create(Author $author): Author;

    public function update(Author $author): bool;

    public function delete(int $id): bool;

    public function count(): int;

    /** @return array<int, array{id: int, full_name: string, books_count: int}> */
    public function top10ByYear(int $year): array;
}
