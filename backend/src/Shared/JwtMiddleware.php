<?php

declare(strict_types=1);

namespace App\Shared;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use HttpSoft\Message\Response;

/**
 * PSR-15 middleware that enforces JWT authentication for protected routes.
 */
final class JwtMiddleware implements MiddlewareInterface
{
    public function __construct(
        private JwtHelper $jwtHelper,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getAttribute('protected', false) !== true) {
            return $handler->handle($request);
        }

        $authHeader = $request->getHeaderLine('Authorization');
        $token = $this->jwtHelper->extractFromHeader($authHeader);

        if ($token === null) {
            return $this->unauthorized('Missing or invalid Authorization header.');
        }

        $user = $this->jwtHelper->decode($token);

        if ($user === null) {
            return $this->unauthorized('Invalid or expired token.');
        }

        $request = $request->withAttribute('user', $user);

        return $handler->handle($request);
    }

    private function unauthorized(string $message): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write(json_encode(['error' => $message]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(401);
    }
}
