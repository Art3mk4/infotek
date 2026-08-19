<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

// Load environment
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
}

use Yiisoft\Db\Pgsql\Connection;
use Yiisoft\Db\Pgsql\Driver;
use Yiisoft\Db\Cache\SchemaCache;
use Yiisoft\Db\Migration\MigrationBuilder;

$dsn = $_ENV['DB_DSN'] ?? 'pgsql:host=db;dbname=books';
$username = $_ENV['DB_USER'] ?? 'yii';
$password = $_ENV['DB_PASSWORD'] ?? 'yiipass';

try {
    echo "Connecting to database...\n";
    $driver = new Driver($dsn, $username, $password);
    $schemaCache = new SchemaCache(new App\Shared\NullCache());
    $db = new Connection($driver, $schemaCache);
    echo "Connected!\n\n";

    echo "Running migrations...\n\n";

    $files = glob(__DIR__ . '/migrations/M*.php');
    sort($files);

    foreach ($files as $file) {
        $className = 'App\\Migration\\' . basename($file, '.php');
        echo "Applying: " . basename($file, '.php') . "\n";

        require_once $file;
        $migration = new $className();
        $builder = new MigrationBuilder($db);

        try {
            $migration->up($builder);
            echo "✓ Done\n\n";
        } catch (\Exception $e) {
            echo "✗ Failed: " . $e->getMessage() . "\n";
            // Continue with other migrations
        }
    }

    echo "All migrations processed!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
