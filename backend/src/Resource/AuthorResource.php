<?php

declare(strict_types=1);

namespace App\Resource;

use App\Domain\Author;

final class AuthorResource implements ResourceInterface
{
    public function __construct(private Author $author) {}

    public function toArray(): array
    {
        return [
            'id' => $this->author->id,
            'full_name' => $this->author->fullName,
        ];
    }

    /**
     * @param Author[] $authors
     */
    public static function collection(array $authors): array
    {
        return array_map(
            fn (Author $author) => (new self($author))->toArray(),
            $authors
        );
    }
}
