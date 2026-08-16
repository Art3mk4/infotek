<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

/**
 * Create subscription table
 */
final class M20260816000005CreateSubscriptionTable implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $b->createTable('subscription', [
            'id' => $b->primaryKey(),
            'author_id' => $b->integer()->notNull(),
            'phone' => $b->string(20)->notNull(),
            'created_at' => $b->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $b->addForeignKey(
            'fk_subscription_author',
            'subscription',
            'author_id',
            'author',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $b->createIndex('idx_subscription_author_id', 'subscription', ['author_id']);
        $b->createIndex('idx_subscription_phone', 'subscription', ['phone']);
        $b->createIndex('unique_author_phone', 'subscription', ['author_id', 'phone'], true);
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('subscription');
    }
}
