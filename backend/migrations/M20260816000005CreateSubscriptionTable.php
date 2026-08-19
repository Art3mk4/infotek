<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Constant\IndexType;
use Yiisoft\Db\Expression\Expression;
use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Schema\Column\ColumnBuilder;

/**
 * Create subscription table
 */
final class M20260816000005CreateSubscriptionTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $b->createTable('subscription', [
            'id' => ColumnBuilder::primaryKey(),
            'author_id' => ColumnBuilder::integer()->notNull(),
            'phone' => ColumnBuilder::string(20)->notNull(),
            'created_at' => ColumnBuilder::timestamp()->defaultValue(new Expression('CURRENT_TIMESTAMP')),
        ]);

        $b->addForeignKey(
            'subscription',
            'fk_subscription_author',
            'author_id',
            'author',
            'id',
            'CASCADE',
        );

        $b->createIndex('subscription', 'unique_author_phone', ['author_id', 'phone'], IndexType::UNIQUE);

        $b->createIndex('subscription', 'idx_subscription_author_id', 'author_id');
        $b->createIndex('subscription', 'idx_subscription_phone', 'phone');
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('subscription');
    }
}
