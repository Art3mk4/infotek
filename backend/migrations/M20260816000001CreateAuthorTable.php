<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

/**
 * Create author table
 */
final class M20260816000001CreateAuthorTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $b->createTable('author', [
            'id' => $b->primaryKey(),
            'full_name' => $b->string(255)->notNull(),
        ]);

        $b->createIndex('idx_author_full_name', 'author', ['full_name']);
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('author');
    }
}
