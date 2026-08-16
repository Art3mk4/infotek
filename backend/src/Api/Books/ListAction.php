<?php

declare(strict_types=1);

namespace App\Api\Books;

use App\Resource\BookResource;
use App\Service\BookService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use HttpSoft\Message\Response;

final class ListAction
{
    public function __construct(
        private BookService $bookService
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();
        $page = max(1, (int)($params['page'] ?? 1));
        $perPage = min(100, max(1, (int)($params['per_page'] ?? 20)));

        $result = $this->bookService->getAll($page, $perPage);

        $response = new Response();
        $response->getBody()->write(json_encode([
            'items' => BookResource::collection($result['items']),
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
