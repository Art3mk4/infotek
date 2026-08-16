<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

/**
 * Create user table
 */
final class M20260816000004CreateUserTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $b->createTable('user', [
            'id' => $b->primaryKey(),
            'username' => $b->string(100)->notNull()->unique(),
            'email' => $b->string(255)->notNull()->unique(),
            'password_hash' => $b->string(255)->notNull(),
            'auth_key' => $b->string(32),
            'status' => $b->smallInteger()->defaultValue(10),
            'created_at' => $b->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $b->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $b->createIndex('idx_user_username', 'user', ['username']);
        $b->createIndex('idx_user_email', 'user', ['email']);
        $b->createIndex('idx_user_status', 'user', ['status']);
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('user');
    }
}
