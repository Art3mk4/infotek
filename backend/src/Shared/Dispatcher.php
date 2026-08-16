<?php

declare(strict_types=1);

namespace App\Shared;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * PSR-15 middleware stack dispatcher.
 */
final class Dispatcher implements RequestHandlerInterface
{
    /**
     * @param array<MiddlewareInterface> $middleware
     */
    public function __construct(
        private array $middleware,
        private RequestHandlerInterface $handler,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if ($this->middleware === []) {
            return $this->handler->handle($request);
        }

        $middleware = array_shift($this->middleware);

        return $middleware->process($request, new self($this->middleware, $this->handler));
    }
}
