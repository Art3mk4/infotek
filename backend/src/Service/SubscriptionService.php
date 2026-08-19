<?php

declare(strict_types=1);

namespace App\Service;

use App\Domain\Subscription;
use App\Domain\SubscriptionRepositoryInterface;
use App\ValueObject\CreateSubscriptionData;

/**
 * Subscription service - handles subscription business logic.
 * Input data is passed through ValueObjects, not raw arrays.
 */
final class SubscriptionService
{
    public function __construct(
        private SubscriptionRepositoryInterface $subscriptionRepository,
    ) {
    }

    public function create(CreateSubscriptionData $data): Subscription
    {
        $phone = $this->normalizePhone($data->phone);
        if (!$this->isValidPhone($phone)) {
            throw new \InvalidArgumentException('Invalid phone number format');
        }

        $subscription = new Subscription(
            authorId: $data->authorId,
            phone: $phone,
        );

        return $this->subscriptionRepository->create($subscription);
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }

    private function isValidPhone(string $phone): bool
    {
        return strlen($phone) >= 10 && strlen($phone) <= 15;
    }
}
