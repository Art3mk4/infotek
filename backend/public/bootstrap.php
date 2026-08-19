<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../.env')) {
    Dotenv\Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();
}

$container = new App\Shared\Container();

// Built eagerly (not just registered as a lazy factory) because ActiveRecord models
// resolve their connection through ConnectionProvider's static registry rather than
// constructor injection (Entity/ActiveRecord instances aren't DI services), so it must
// be set up before any model is touched, regardless of which action handles the request.
$dbConnection = App\Shared\DbConnectionFactory::create();

$container->set(\Yiisoft\Db\Connection\ConnectionInterface::class, static fn () => $dbConnection);

// The container only autowires concrete classes (see Container::has()), so repository
// interfaces need an explicit binding to their infrastructure implementation.
$container->set(App\Domain\BookRepositoryInterface::class, static fn (App\Shared\Container $c) => $c->get(App\Repository\BookRepository::class));
$container->set(App\Domain\AuthorRepositoryInterface::class, static fn (App\Shared\Container $c) => $c->get(App\Repository\AuthorRepository::class));
$container->set(App\Domain\SubscriptionRepositoryInterface::class, static fn (App\Shared\Container $c) => $c->get(App\Repository\SubscriptionRepository::class));
$container->set(App\Domain\UserRepositoryInterface::class, static fn (App\Shared\Container $c) => $c->get(App\Repository\UserRepository::class));

$container->set(\Psr\Http\Client\ClientInterface::class, static function (): \Psr\Http\Client\ClientInterface {
    return new GuzzleHttp\Client(['timeout' => 5.0, 'http_errors' => false]);
});

$container->set(\Psr\Http\Message\RequestFactoryInterface::class, static function (): \Psr\Http\Message\RequestFactoryInterface {
    return new GuzzleHttp\Psr7\HttpFactory();
});

$container->set(\Psr\Http\Message\StreamFactoryInterface::class, static function (): \Psr\Http\Message\StreamFactoryInterface {
    return new GuzzleHttp\Psr7\HttpFactory();
});

$container->set(\Psr\Log\LoggerInterface::class, static function (): \Psr\Log\LoggerInterface {
    return new App\Shared\StderrLogger();
});

$container->set(App\Shared\JwtHelper::class, static function (): App\Shared\JwtHelper {
    return new App\Shared\JwtHelper($_ENV['JWT_SECRET'] ?? null);
});

$container->set(App\Service\NotificationService::class, static function (App\Shared\Container $c): App\Service\NotificationService {
    return new App\Service\NotificationService(
        $c->get(App\Domain\SubscriptionRepositoryInterface::class),
        $c->get(\Psr\Http\Client\ClientInterface::class),
        $c->get(\Psr\Http\Message\RequestFactoryInterface::class),
        $c->get(\Psr\Http\Message\StreamFactoryInterface::class),
        $c->get(\Psr\Log\LoggerInterface::class),
        $_ENV['SMSPILOT_API_KEY'] ?? 'emulator',
    );
});

return $container;
