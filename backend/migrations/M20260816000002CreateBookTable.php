<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Expression\Expression;
use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Schema\Column\ColumnBuilder;

/**
 * Create book table
 */
final class M20260816000002CreateBookTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $b->createTable('book', [
            'id' => ColumnBuilder::primaryKey(),
            'title' => ColumnBuilder::string(255)->notNull(),
            'year' => ColumnBuilder::integer()->notNull(),
            'description' => ColumnBuilder::text(),
            'isbn' => ColumnBuilder::string(20),
            'cover_image' => ColumnBuilder::string(255),
            'created_at' => ColumnBuilder::timestamp()->defaultValue(new Expression('CURRENT_TIMESTAMP')),
            'updated_at' => ColumnBuilder::timestamp()->defaultValue(new Expression('CURRENT_TIMESTAMP')),
        ]);

        $b->createIndex('book', 'idx_book_title', 'title');
        $b->createIndex('book', 'idx_book_year', 'year');
        $b->createIndex('book', 'idx_book_isbn', 'isbn');
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('book');
    }
}
