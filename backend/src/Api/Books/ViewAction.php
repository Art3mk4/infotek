<?php

declare(strict_types=1);

namespace App\Api\Books;

use App\Resource\BookResource;
use App\Service\BookService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use HttpSoft\Message\Response;

final class ViewAction
{
    public function __construct(
        private BookService $bookService
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = (int)$request->getAttribute('id');
        $book = $this->bookService->getById($id);

        if (!$book) {
            return $this->errorResponse('Book not found', 404);
        }

        return $this->jsonResponse((new BookResource($book))->toArray());
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
