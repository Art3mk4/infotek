<?php

declare(strict_types=1);

namespace App\Api\Subscriptions;

use App\Resource\SubscriptionResource;
use App\Service\SubscriptionService;
use App\ValueObject\CreateSubscriptionData;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use HttpSoft\Message\Response;

final class CreateAction
{
    public function __construct(
        private SubscriptionService $subscriptionService
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = json_decode($request->getBody()->getContents(), true);
        $data = CreateSubscriptionData::fromArray($body);

        try {
            $subscription = $this->subscriptionService->create($data);
            return $this->jsonResponse((new SubscriptionResource($subscription))->toArray(), 201);
        } catch (\RuntimeException $e) {
            return $this->errorResponse($e->getMessage(), is_int($e->getCode()) && $e->getCode() >= 100 && $e->getCode() < 600 ? $e->getCode() : 400);
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
