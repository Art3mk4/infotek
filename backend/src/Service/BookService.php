<?php

declare(strict_types=1);

namespace App\Service;

use App\Domain\Book;
use App\Repository\BookRepository;
use App\Service\NotificationService;
use App\ValueObject\CreateBookData;
use App\ValueObject\UpdateBookData;

/**
 * Book service - handles book business logic.
 * Input data is passed through ValueObjects, not raw arrays.
 */
final class BookService
{
    public function __construct(
        private BookRepository $bookRepository,
        private NotificationService $notificationService,
    ) {
    }

    public function getAll(int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;
        $books = $this->bookRepository->findAll($perPage, $offset);
        $total = $this->bookRepository->count();

        return [
            'items' => $books,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => (int)ceil($total / $perPage),
        ];
    }

    public function getById(int $id): ?Book
    {
        return $this->bookRepository->findById($id);
    }

    public function create(CreateBookData $data): Book
    {
        $book = new Book(
            title: $data->title,
            year: $data->year,
            description: $data->description,
            isbn: $data->isbn,
            coverImage: $data->coverImage,
        );

        $book = $this->bookRepository->create($book);

        if (!empty($data->authorIds)) {
            $this->bookRepository->linkAuthors($book->id, $data->authorIds);
            // Reload to get authors
            $book = $this->bookRepository->findById($book->id);
        }

        // Send SMS notifications to subscribers
        try {
            $this->notificationService->notifySubscribers($book);
        } catch (\Exception $e) {
            // Log error but don't fail book creation
            error_log("Failed to send notifications: " . $e->getMessage());
        }

        return $book;
    }

    public function update(int $id, UpdateBookData $data): ?Book
    {
        $book = $this->bookRepository->findById($id);
        if (!$book) {
            return null;
        }

        $book->title = $data->title ?? $book->title;
        $book->year = $data->year ?? $book->year;
        $book->description = $data->description ?? $book->description;
        $book->isbn = $data->isbn ?? $book->isbn;
        $book->coverImage = $data->coverImage ?? $book->coverImage;

        $this->bookRepository->update($book);

        if ($data->authorIds !== null) {
            $this->bookRepository->linkAuthors($book->id, $data->authorIds);
        }

        return $this->bookRepository->findById($id);
    }

    public function delete(int $id): bool
    {
        return $this->bookRepository->delete($id);
    }
}
