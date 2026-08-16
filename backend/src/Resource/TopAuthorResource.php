<?php

declare(strict_types=1);

namespace App\Resource;

final class TopAuthorResource implements ResourceInterface
{
    public function __construct(private array $row) {}

    public function toArray(): array
    {
        return [
            'id' => $this->row['id'] ?? null,
            'full_name' => $this->row['full_name'] ?? null,
            'books_count' => $this->row['books_count'] ?? null,
        ];
    }

    public static function collection(array $rows): array
    {
        return array_map(
            fn (array $row) => (new self($row))->toArray(),
            $rows
        );
    }
}
