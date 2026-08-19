<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../.env')) {
    Dotenv\Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();
}

$container = new App\Shared\Container();

$container->set(\Yiisoft\Db\Connection\ConnectionInterface::class, static function (): \Yiisoft\Db\Connection\ConnectionInterface {
    $host = $_ENV['DB_HOST'] ?? 'db';
    $dbname = $_ENV['DB_NAME'] ?? 'books';
    $user = $_ENV['DB_USER'] ?? 'yii';
    $password = $_ENV['DB_PASSWORD'] ?? 'yiipass';

    $driver = new \Yiisoft\Db\Pgsql\Driver("pgsql:host={$host};dbname={$dbname}", $user, $password);
    $schemaCache = new \Yiisoft\Db\Cache\SchemaCache(new App\Shared\NullCache());

    return new \Yiisoft\Db\Pgsql\Connection($driver, $schemaCache);
});

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
