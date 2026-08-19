<?php

declare(strict_types=1);

namespace App\Service;

use App\Domain\Author;
use App\Domain\AuthorRepositoryInterface;
use App\ValueObject\CreateAuthorData;
use App\ValueObject\UpdateAuthorData;

/**
 * Author service - handles author business logic.
 * Input data is passed through ValueObjects, not raw arrays.
 */
final class AuthorService
{
    public function __construct(
        private AuthorRepositoryInterface $authorRepository,
    ) {
    }

    public function getAll(int $page = 1, int $perPage = 50): array
    {
        $offset = ($page - 1) * $perPage;
        $authors = $this->authorRepository->findAll($perPage, $offset);
        $total = $this->authorRepository->count();

        return [
            'items' => $authors,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int)ceil($total / $perPage),
        ];
    }

    public function getById(int $id): ?Author
    {
        return $this->authorRepository->findById($id);
    }

    public function getByIdWithRelations(int $id): ?Author
    {
        return $this->authorRepository->findByIdWithRelations($id);
    }

    public function create(CreateAuthorData $data): Author
    {
        $author = new Author();
        $author->full_name = $data->fullName;

        return $this->authorRepository->create($author);
    }

    public function update(int $id, UpdateAuthorData $data): ?Author
    {
        $author = $this->authorRepository->findById($id);
        if (!$author) {
            return null;
        }

        $author->full_name = $data->fullName;
        $this->authorRepository->update($author);

        return $author;
    }

    public function delete(int $id): bool
    {
        return $this->authorRepository->delete($id);
    }
}
