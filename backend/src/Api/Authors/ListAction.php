<?php

declare(strict_types=1);

namespace App\Api\Authors;

use App\Resource\AuthorResource;
use App\Service\AuthorService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use HttpSoft\Message\Response;

final class ListAction
{
    public function __construct(
        private AuthorService $authorService
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();
        $page = max(1, (int)($params['page'] ?? 1));
        $perPage = min(100, max(1, (int)($params['per_page'] ?? 50)));

        $result = $this->authorService->getAll($page, $perPage);

        $response = new Response();
        $response->getBody()->write(json_encode([
            'items' => AuthorResource::collection($result['items']),
            'total' => $result['total'],
            'page' => $result['page'],
            'per_page' => $result['per_page'],
            'total_pages' => $result['total_pages'],
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
