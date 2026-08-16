<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\AuthorRepository;

/**
 * Author report service - generates reports on authors.
 * All database access is delegated to the repository layer.
 */
final class AuthorReportService
{
    public function __construct(
        private AuthorRepository $authorRepository
    ) {
    }

    public function top10ByYear(int $year): array
    {
        return $this->authorRepository->top10ByYear($year);
    }
}
