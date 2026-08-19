<?php

declare(strict_types=1);

namespace App\Service;

use App\Domain\User;
use App\Domain\UserRepositoryInterface;
use App\Shared\JwtHelper;
use App\ValueObject\LoginCredentials;

/**
 * Authentication service - handles user login and token generation.
 * Input data is passed through ValueObjects, not raw arrays.
 */
final class AuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private JwtHelper $jwtHelper,
    ) {
    }

    public function authenticate(LoginCredentials $credentials): User
    {
        $user = $this->userRepository->findByUsername($credentials->username);

        if (!$user || !$user->validatePassword($credentials->password)) {
            throw new \RuntimeException('Invalid credentials', 401);
        }

        return $user;
    }

    public function generateToken(User $user): string
    {
        return $this->jwtHelper->encode([
            'user_id' => $user->id,
            'username' => $user->username,
        ]);
    }
}
