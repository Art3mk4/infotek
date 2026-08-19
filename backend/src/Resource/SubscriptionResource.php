<?php

declare(strict_types=1);

namespace App\Resource;

use App\Domain\Subscription;

final class SubscriptionResource implements ResourceInterface
{
    public function __construct(private Subscription $subscription) {}

    public function toArray(): array
    {
        return [
            'id' => $this->subscription->id,
            'author_id' => $this->subscription->author_id,
            'phone' => $this->subscription->phone,
            'created_at' => $this->subscription->created_at?->format('Y-m-d H:i:s.u'),
        ];
    }

    /**
     * @param Subscription[] $subscriptions
     */
    public static function collection(array $subscriptions): array
    {
        return array_map(
            fn (Subscription $subscription) => (new self($subscription))->toArray(),
            $subscriptions
        );
    }
}
