<?php

declare(strict_types=1);

namespace App\Api\Authors;

use App\Resource\AuthorResource;
use App\Service\AuthorService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use HttpSoft\Message\Response;

final class ViewAction
{
    public function __construct(
        private AuthorService $authorService
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int)$request->getAttribute('id');
        $author = $this->authorService->getById($id);

        if (!$author) {
            return $this->errorResponse('Author not found', 404);
        }

        return $this->jsonResponse((new AuthorResource($author))->toArray());
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
