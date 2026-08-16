<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

/**
 * Create book_author junction table
 */
final class M20260816000003CreateBookAuthorTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $b->createTable('book_author', [
            'book_id' => $b->integer()->notNull(),
            'author_id' => $b->integer()->notNull(),
        ]);

        $b->addPrimaryKey('pk_book_author', 'book_author', ['book_id', 'author_id']);

        $b->addForeignKey(
            'fk_book_author_book',
            'book_author',
            'book_id',
            'book',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $b->addForeignKey(
            'fk_book_author_author',
            'book_author',
            'author_id',
            'author',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $b->createIndex('idx_book_author_book_id', 'book_author', 'book_id');
        $b->createIndex('idx_book_author_author_id', 'book_author', 'author_id');
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('book_author');
    }
}
