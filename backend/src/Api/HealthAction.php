<?php

declare(strict_types=1);

namespace App\Api;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use HttpSoft\Message\Response;

/**
 * Health check action
 */
final class HealthAction
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $data = [
            'status' => 'ok',
            'timestamp' => time(),
            'version' => '1.0.0'
        ];

        $response = new Response();
        $response->getBody()->write(json_encode($data));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
