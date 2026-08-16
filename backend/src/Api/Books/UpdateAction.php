<?php

declare(strict_types=1);

namespace App\Api\Books;

use App\Resource\BookResource;
use App\Service\BookService;
use App\ValueObject\UpdateBookData;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use HttpSoft\Message\Response;

final class UpdateAction
{
    public function __construct(
        private BookService $bookService
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // TODO: Add JWT authentication check here

        $id = (int)$request->getAttribute('id');
        $body = json_decode($request->getBody()->getContents(), true);
        $data = UpdateBookData::fromArray($body);

        try {
            $book = $this->bookService->update($id, $data);
            if (!$book) {
                return $this->errorResponse('Book not found', 404);
            }
            return $this->jsonResponse((new BookResource($book))->toArray());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
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
