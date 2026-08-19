<?php

declare(strict_types=1);

namespace App\Shared;

use Yiisoft\Cache\ArrayCache;
use Yiisoft\Db\Cache\SchemaCache;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Connection\ConnectionProvider;
use Yiisoft\Db\Pgsql\Connection;
use Yiisoft\Db\Pgsql\Driver;

/**
 * Builds the app's single database connection from environment variables and registers it with
 * ActiveRecord's {@see ConnectionProvider}, since Domain models resolve their connection through
 * that static registry rather than constructor injection. Shared by the web entrypoint
 * ({@see \bootstrap()}) and the migration console entrypoint so both see the same database.
 */
final class DbConnectionFactory
{
    public static function create(): ConnectionInterface
    {
        $host = $_ENV['DB_HOST'] ?? 'db';
        $dbName = $_ENV['DB_NAME'] ?? 'books';
        $user = $_ENV['DB_USER'] ?? 'yii';
        $password = $_ENV['DB_PASSWORD'] ?? 'yiipass';

        $driver = new Driver("pgsql:host={$host};dbname={$dbName}", $user, $password);
        $schemaCache = new SchemaCache(new ArrayCache());
        $connection = new Connection($driver, $schemaCache);

        ConnectionProvider::set($connection);

        return $connection;
    }
}
