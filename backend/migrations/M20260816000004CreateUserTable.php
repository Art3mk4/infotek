<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Expression\Expression;
use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Schema\Column\ColumnBuilder;

/**
 * Create user table
 */
final class M20260816000004CreateUserTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $b->createTable('user', [
            'id' => ColumnBuilder::primaryKey(),
            'username' => ColumnBuilder::string(100)->notNull()->unique(),
            'email' => ColumnBuilder::string(255)->notNull()->unique(),
            'password_hash' => ColumnBuilder::string(255)->notNull(),
            'auth_key' => ColumnBuilder::string(32),
            'status' => ColumnBuilder::smallint()->defaultValue(10),
            'created_at' => ColumnBuilder::timestamp()->defaultValue(new Expression('CURRENT_TIMESTAMP')),
            'updated_at' => ColumnBuilder::timestamp()->defaultValue(new Expression('CURRENT_TIMESTAMP')),
        ]);

        $b->createIndex('user', 'idx_user_username', 'username');
        $b->createIndex('user', 'idx_user_email', 'email');
        $b->createIndex('user', 'idx_user_status', 'status');
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('user');
    }
}
