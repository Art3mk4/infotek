<?php

declare(strict_types=1);

/**
 * Application route map.
 *
 * Each entry: [HTTP method, path pattern, action class, protected?]
 * Path placeholders use the syntax {name:regex}.
 */
return [
    ['GET', '/health', \App\Api\HealthAction::class, false],

    ['POST', '/api/auth/login', \App\Api\Auth\LoginAction::class, false],

    ['GET', '/api/books', \App\Api\Books\ListAction::class, false],
    ['GET', '/api/books/{id:\d+}', \App\Api\Books\ViewAction::class, false],
    ['POST', '/api/books', \App\Api\Books\CreateAction::class, true],
    ['PUT', '/api/books/{id:\d+}', \App\Api\Books\UpdateAction::class, true],
    ['DELETE', '/api/books/{id:\d+}', \App\Api\Books\DeleteAction::class, true],

    ['GET', '/api/authors', \App\Api\Authors\ListAction::class, false],
    ['GET', '/api/authors/{id:\d+}', \App\Api\Authors\ViewAction::class, false],
    ['POST', '/api/authors', \App\Api\Authors\CreateAction::class, true],
    ['PUT', '/api/authors/{id:\d+}', \App\Api\Authors\UpdateAction::class, true],
    ['DELETE', '/api/authors/{id:\d+}', \App\Api\Authors\DeleteAction::class, true],

    ['POST', '/api/subscriptions', \App\Api\Subscriptions\CreateAction::class, false],

    ['GET', '/api/report/top-authors', \App\Api\Report\TopAuthorsAction::class, false],
];
