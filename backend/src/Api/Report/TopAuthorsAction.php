<?php

declare(strict_types=1);

namespace App\Api\Report;

use App\Resource\TopAuthorResource;
use App\Service\AuthorReportService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use HttpSoft\Message\Response;

final class TopAuthorsAction
{
    public function __construct(
        private AuthorReportService $reportService
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $params = $request->getQueryParams();
        $year = (int)($params['year'] ?? date('Y'));

        $authors = $this->reportService->top10ByYear($year);

        $response = new Response();
        $response->getBody()->write(json_encode([
            'year' => $year,
            'authors' => TopAuthorResource::collection($authors)
        ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
