<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Schema\Column\ColumnBuilder;

/**
 * Create book_author junction table
 */
final class M20260816000003CreateBookAuthorTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $b->createTable('book_author', [
            'book_id' => ColumnBuilder::integer()->notNull(),
            'author_id' => ColumnBuilder::integer()->notNull(),
        ]);

        $b->addPrimaryKey('book_author', 'pk_book_author', ['book_id', 'author_id']);

        $b->addForeignKey(
            'book_author',
            'fk_book_author_book',
            'book_id',
            'book',
            'id',
            'CASCADE',
        );
        $b->addForeignKey(
            'book_author',
            'fk_book_author_author',
            'author_id',
            'author',
            'id',
            'CASCADE',
        );

        $b->createIndex('book_author', 'idx_book_author_book_id', 'book_id');
        $b->createIndex('book_author', 'idx_book_author_author_id', 'author_id');
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('book_author');
    }
}
