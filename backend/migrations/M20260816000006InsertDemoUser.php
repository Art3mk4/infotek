<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

/**
 * Seed the demo admin user (admin / admin123).
 */
final class M20260816000006InsertDemoUser implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $b->insert('user', [
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password_hash' => '$2y$10$Biw34o4ujKm3qP.1L/7BHOJVf/HiSa0irf2DZO1S5PpVlJNBKd0JK',
            'auth_key' => 'test-auth-key-12345678901234',
            'status' => 10,
        ]);
    }

    public function down(MigrationBuilder $b): void
    {
        $b->delete('user', ['username' => 'admin']);
    }
}
