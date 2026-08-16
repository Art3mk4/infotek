<?php

declare(strict_types=1);

namespace App\Api\Authors;

use App\Resource\AuthorResource;
use App\Service\AuthorService;
use App\ValueObject\UpdateAuthorData;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use HttpSoft\Message\Response;

final class UpdateAction
{
    public function __construct(
        private AuthorService $authorService
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // TODO: Add JWT authentication check here

        $id = (int)$request->getAttribute('id');
        $body = json_decode($request->getBody()->getContents(), true);
        $data = UpdateAuthorData::fromArray($body);

        try {
            $author = $this->authorService->update($id, $data);
            if (!$author) {
                return $this->errorResponse('Author not found', 404);
            }
            return $this->jsonResponse((new AuthorResource($author))->toArray());
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
