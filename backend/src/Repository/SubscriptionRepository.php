<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\Subscription;
use App\Domain\SubscriptionRepositoryInterface;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Exception\IntegrityException;
use Yiisoft\Db\Query\Query;

/**
 * Subscription repository
 */
final class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function __construct(
        private ConnectionInterface $db
    ) {
    }

    public function create(Subscription $subscription): Subscription
    {
        try {
            $this->db->createCommand()->insert('subscription', [
                'author_id' => $subscription->authorId,
                'phone' => $subscription->phone,
            ])->execute();
        } catch (IntegrityException $e) {
            throw new \RuntimeException('Subscription already exists for this author and phone', 409, $e);
        }

        $subscription->id = (int)$this->db->getLastInsertID('subscription_id_seq');

        return $subscription;
    }

    public function findByAuthorIds(array $authorIds): array
    {
        if (empty($authorIds)) {
            return [];
        }

        $rows = (new Query($this->db))
            ->from('subscription')
            ->where(['author_id' => $authorIds])
            ->all();

        return array_map(static fn(array $row) => Subscription::fromArray($row), $rows);
    }

    public function findByAuthorId(int $authorId): array
    {
        $rows = (new Query($this->db))
            ->from('subscription')
            ->where(['author_id' => $authorId])
            ->all();

        return array_map(static fn(array $row) => Subscription::fromArray($row), $rows);
    }
}
