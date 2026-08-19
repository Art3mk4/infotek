<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../.env')) {
    Dotenv\Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();
}

$container = new App\Shared\Container();

$dbConnection = App\Shared\DbConnectionFactory::create();
$container->set(\Yiisoft\Db\Connection\ConnectionInterface::class, static fn () => $dbConnection);

$container->set(
    \Yiisoft\Db\Migration\Informer\MigrationInformerInterface::class,
    static fn () => new \Yiisoft\Db\Migration\Informer\ConsoleMigrationInformer(),
);

$container->set(
    \Yiisoft\Injector\Injector::class,
    static fn (App\Shared\Container $c) => new \Yiisoft\Injector\Injector($c),
);

$container->set(
    \Yiisoft\Db\Migration\Service\Generate\CreateService::class,
    static fn (App\Shared\Container $c) => new \Yiisoft\Db\Migration\Service\Generate\CreateService(
        $c->get(\Yiisoft\Db\Connection\ConnectionInterface::class),
        useTablePrefix: false,
    ),
);

$container->set(
    \Yiisoft\Db\Migration\Service\MigrationService::class,
    static function (App\Shared\Container $c): \Yiisoft\Db\Migration\Service\MigrationService {
        $service = new \Yiisoft\Db\Migration\Service\MigrationService(
            $c->get(\Yiisoft\Db\Connection\ConnectionInterface::class),
            $c->get(\Yiisoft\Injector\Injector::class),
            $c->get(\Yiisoft\Db\Migration\Migrator::class),
        );
        // Namespace-based only: MigrationService rejects setting both newMigrationNamespace
        // and newMigrationPath (migrate:create), and setting both sourceNamespaces and
        // sourcePaths makes every migration show up twice (migrate:up).
        $service->setNewMigrationNamespace('App\\Migration');
        $service->setSourceNamespaces(['App\\Migration']);

        return $service;
    },
);

return $container;
