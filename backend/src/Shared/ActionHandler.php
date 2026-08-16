<?php

declare(strict_types=1);

namespace App\Shared;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use HttpSoft\Message\Response;

/**
 * Resolves the action class stored in the request and invokes it.
 */
final class ActionHandler implements RequestHandlerInterface
{
    public function __construct(
        private ContainerInterface $container,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $actionClass = $request->getAttribute('action');

        if (!is_string($actionClass) || $actionClass === '') {
            $response = new Response();
            $response->getBody()->write(json_encode(['error' => 'Not found']));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $action = $this->container->get($actionClass);

        return $action->handle($request);
    }
}
