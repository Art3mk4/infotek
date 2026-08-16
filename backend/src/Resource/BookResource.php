<?php

declare(strict_types=1);

namespace App\Resource;

use App\Domain\Book;

final class BookResource implements ResourceInterface
{
    public function __construct(private Book $book) {}

    public function toArray(): array
    {
        return [
            'id' => $this->book->id,
            'title' => $this->book->title,
            'year' => $this->book->year,
            'description' => $this->book->description,
            'isbn' => $this->book->isbn,
            'cover_image' => $this->book->coverImage,
            'created_at' => $this->book->createdAt,
            'updated_at' => $this->book->updatedAt,
            'authors' => AuthorResource::collection($this->book->authors),
        ];
    }

    /**
     * @param Book[] $books
     */
    public static function collection(array $books): array
    {
        return array_map(
            fn (Book $book) => (new self($book))->toArray(),
            $books
        );
    }
}
