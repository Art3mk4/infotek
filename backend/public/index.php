<?php

declare(strict_types=1);

/** @var App\Shared\Container $container */
$container = require __DIR__ . '/bootstrap.php';

$request = GuzzleHttp\Psr7\ServerRequest::fromGlobals();

$method = $request->getMethod();
$path = $request->getUri()->getPath();

$routes = require __DIR__ . '/../config/routes.php';

$matchedRoute = null;
$routeParams = [];

foreach ($routes as $route) {
    [$routeMethod, $routePath, $actionClass, $protected] = $route;

    if ($routeMethod !== $method) {
        continue;
    }

    $pattern = '#^' . preg_replace('/\{(\w+):([^}]+)\}/', '(?P<$1>$2)', $routePath) . '$#';

    if (!preg_match($pattern, $path, $matches)) {
        continue;
    }

    $matchedRoute = $route;

    foreach ($matches as $key => $value) {
        if (is_string($key)) {
            $routeParams[$key] = $value;
        }
    }

    break;
}

if ($matchedRoute === null) {
    $response = new HttpSoft\Message\Response();
    $response->getBody()->write(json_encode(['error' => 'Not found']));
    $response = $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(404);

    emitResponse($response);
    exit;
}

foreach ($routeParams as $key => $value) {
    $request = $request->withAttribute($key, (int) $value);
}

$request = $request
    ->withAttribute('action', $matchedRoute[2])
    ->withAttribute('protected', $matchedRoute[3]);

$allowedOrigin = $_ENV['FRONTEND_URL'] ?? 'http://localhost:3000';

$dispatcher = new App\Shared\Dispatcher(
    [
        new App\Shared\CorsMiddleware($allowedOrigin),
        new App\Shared\JwtMiddleware($container->get(App\Shared\JwtHelper::class)),
    ],
    new App\Shared\ActionHandler($container),
);

$response = $dispatcher->handle($request);

emitResponse($response);

function emitResponse(Psr\Http\Message\ResponseInterface $response): void
{
    http_response_code($response->getStatusCode());

    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            header("{$name}: {$value}", false);
        }
    }

    $body = $response->getBody();
    $body->rewind();

    echo $body->getContents();
}
