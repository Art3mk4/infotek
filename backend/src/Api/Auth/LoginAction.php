<?php

declare(strict_types=1);

namespace App\Api\Auth;

use App\Resource\UserResource;
use App\Service\AuthService;
use App\ValueObject\LoginCredentials;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use HttpSoft\Message\Response;

final class LoginAction
{
    public function __construct(
        private AuthService $authService
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true);
        $credentials = LoginCredentials::fromArray($body);

        try {
            $user = $this->authService->authenticate($credentials);
            $token = $this->authService->generateToken($user);

            return $this->jsonResponse([
                'token' => $token,
                'user' => (new UserResource($user))->toArray()
            ]);
        } catch (\RuntimeException $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 401);
        }
    }

    private function jsonResponse(array $data, int $status = 200): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($status);
    }

    private function errorResponse(string $message, int $status): ResponseInterface
    {
        return $this->jsonResponse(['error' => $message], $status);
    }
}
