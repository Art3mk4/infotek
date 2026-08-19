<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Schema\Column\ColumnBuilder;

/**
 * Create author table
 */
final class M20260816000001CreateAuthorTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $b->createTable('author', [
            'id' => ColumnBuilder::primaryKey(),
            'full_name' => ColumnBuilder::string(255)->notNull(),
        ]);

        $b->createIndex('author', 'idx_author_full_name', 'full_name');
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('author');
    }
}
