<?php

declare(strict_types=1);

namespace App\Resource;

use App\Domain\User;

final class UserResource implements ResourceInterface
{
    public function __construct(private User $user) {}

    public function toArray(): array
    {
        return [
            'id' => $this->user->id,
            'username' => $this->user->username,
            'email' => $this->user->email,
            'status' => $this->user->status,
            'created_at' => $this->user->created_at?->format('Y-m-d H:i:s.u'),
            'updated_at' => $this->user->updated_at?->format('Y-m-d H:i:s.u'),
        ];
    }
}
