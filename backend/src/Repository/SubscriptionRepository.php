<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\Subscription;
use App\Domain\SubscriptionRepositoryInterface;
use Yiisoft\Db\Exception\IntegrityException;

/**
 * Subscription repository
 */
final class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function create(Subscription $subscription): Subscription
    {
        try {
            $subscription->insert();
        } catch (IntegrityException $e) {
            throw new \RuntimeException('Subscription already exists for this author and phone', 409, $e);
        }

        return $subscription;
    }

    public function findByAuthorIds(array $authorIds): array
    {
        if (empty($authorIds)) {
            return [];
        }

        return Subscription::findAll(['author_id' => $authorIds]);
    }

    public function findByAuthorId(int $authorId): array
    {
        return Subscription::findAll(['author_id' => $authorId]);
    }
}
