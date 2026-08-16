<?php

declare(strict_types=1);

namespace App\Shared;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * JWT helper for encoding and decoding tokens
 */
final class JwtHelper
{
    private string $secret;
    private string $algorithm = 'HS256';

    public function __construct(?string $secret = null)
    {
        $this->secret = $secret ?? ($_ENV['JWT_SECRET'] ?? 'default-secret-key');
    }

    public function encode(array $payload, int $ttl = 3600): string
    {
        $payload['iat'] = time();
        $payload['exp'] = time() + $ttl;

        return JWT::encode($payload, $this->secret, $this->algorithm);
    }

    public function decode(string $token): ?object
    {
        try {
            return JWT::decode($token, new Key($this->secret, $this->algorithm));
        } catch (\Exception $e) {
            return null;
        }
    }

    public function extractFromHeader(string $authHeader): ?string
    {
        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $matches[1];
        }
        return null;
    }
}
