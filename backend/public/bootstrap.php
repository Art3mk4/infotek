<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../.env')) {
    Dotenv\Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();
}

$container = new App\Shared\Container();

$container->set(PDO::class, static function (): PDO {
    $host = $_ENV['DB_HOST'] ?? 'db';
    $dbname = $_ENV['DB_NAME'] ?? 'books';
    $user = $_ENV['DB_USER'] ?? 'yii';
    $password = $_ENV['DB_PASSWORD'] ?? 'yiipass';

    $pdo = new PDO("pgsql:host={$host};dbname={$dbname}", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
});

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
        $c->get(App\Repository\SubscriptionRepository::class),
        $c->get(\Psr\Http\Client\ClientInterface::class),
        $c->get(\Psr\Http\Message\RequestFactoryInterface::class),
        $c->get(\Psr\Http\Message\StreamFactoryInterface::class),
        $c->get(\Psr\Log\LoggerInterface::class),
        $_ENV['SMSPILOT_API_KEY'] ?? 'emulator',
    );
});

return $container;
