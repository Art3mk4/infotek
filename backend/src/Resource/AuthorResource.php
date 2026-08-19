<?php

declare(strict_types=1);

namespace App\Resource;

use App\Domain\Author;

final class AuthorResource implements ResourceInterface
{
    public function __construct(
        private Author $author,
        private bool $withRelations = false,
    ) {
    }

    public function toArray(): array
    {
        $data = [
            'id' => $this->author->id,
            'full_name' => $this->author->full_name,
        ];

        if ($this->withRelations) {
            $data['books'] = BookResource::collection($this->author->books);
            $data['subscriptions'] = SubscriptionResource::collection($this->author->subscriptions);
        }

        return $data;
    }

    /**
     * @param Author[] $authors
     */
    public static function collection(array $authors, bool $withRelations = false): array
    {
        return array_map(
            fn (Author $author) => (new self($author, $withRelations))->toArray(),
            $authors
        );
    }
}
