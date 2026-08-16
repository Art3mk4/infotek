<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

/**
 * Create book table
 */
final class M20260816000002CreateBookTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $b->createTable('book', [
            'id' => $b->primaryKey(),
            'title' => $b->string(255)->notNull(),
            'year' => $b->integer()->notNull(),
            'description' => $b->text(),
            'isbn' => $b->string(20),
            'cover_image' => $b->string(255),
            'created_at' => $b->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $b->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $b->createIndex('idx_book_title', 'book', ['title']);
        $b->createIndex('idx_book_year', 'book', ['year']);
        $b->createIndex('idx_book_isbn', 'book', ['isbn']);
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('book');
    }
}
