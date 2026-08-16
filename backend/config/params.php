<?php

declare(strict_types=1);

return [
    'yiisoft/db-migration' => [
        'newMigrationNamespace' => 'App\\Migration',
        'sourceNamespaces' => ['App\\Migration'],
        'createPath' => '@root/migrations',
    ],
];
