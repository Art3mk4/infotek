<?php

declare(strict_types=1);

namespace App\Domain;

interface SubscriptionRepositoryInterface
{
    public function create(Subscription $subscription): Subscription;

    /**
     * @param int[] $authorIds
     * @return Subscription[]
     */
    public function findByAuthorIds(array $authorIds): array;

    /** @return Subscription[] */
    public function findByAuthorId(int $authorId): array;
}
