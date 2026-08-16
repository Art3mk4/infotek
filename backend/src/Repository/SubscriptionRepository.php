<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\Subscription;
use PDO;

/**
 * Subscription repository
 */
final class SubscriptionRepository
{
    public function __construct(
        private PDO $db
    ) {
    }

    public function create(Subscription $subscription): Subscription
    {
        $stmt = $this->db->prepare(
            'INSERT INTO subscription (author_id, phone) VALUES (:author_id, :phone)'
        );

        try {
            $stmt->execute([
                ':author_id' => $subscription->authorId,
                ':phone' => $subscription->phone,
            ]);

            $subscription->id = (int)$this->db->lastInsertId();
            return $subscription;
        } catch (\PDOException $e) {
            // Handle duplicate key error
            if ($e->getCode() === '23000') {
                throw new \RuntimeException('Subscription already exists for this author and phone', 409);
            }
            throw $e;
        }
    }

    public function findByAuthorIds(array $authorIds): array
    {
        if (empty($authorIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($authorIds), '?'));
        $stmt = $this->db->prepare(
            "SELECT * FROM subscription WHERE author_id IN ($placeholders)"
        );
        $stmt->execute($authorIds);

        return array_map(
            fn($row) => Subscription::fromArray($row),
            $stmt->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function findByAuthorId(int $authorId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM subscription WHERE author_id = :author_id');
        $stmt->execute([':author_id' => $authorId]);

        return array_map(
            fn($row) => Subscription::fromArray($row),
            $stmt->fetchAll(PDO::FETCH_ASSOC)
        );
    }
}
