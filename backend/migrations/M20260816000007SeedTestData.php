<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

/**
 * Seed the demo catalog: authors, books, book-author links and a handful of subscriptions.
 */
final class M20260816000007SeedTestData implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $b->insertBatch('author', [
            ['id' => 1, 'full_name' => 'Fyodor Dostoevsky'],
            ['id' => 2, 'full_name' => 'Leo Tolstoy'],
            ['id' => 3, 'full_name' => 'Anton Chekhov'],
            ['id' => 4, 'full_name' => 'Alexander Pushkin'],
            ['id' => 5, 'full_name' => 'Ivan Turgenev'],
            ['id' => 6, 'full_name' => 'Mikhail Bulgakov'],
            ['id' => 7, 'full_name' => 'Nikolai Gogol'],
            ['id' => 8, 'full_name' => 'Vladimir Nabokov'],
            ['id' => 9, 'full_name' => 'Boris Pasternak'],
            ['id' => 10, 'full_name' => 'Maxim Gorky'],
        ]);

        $b->insertBatch('book', [
            ['id' => 1, 'title' => 'Crime and Punishment', 'year' => 2024, 'description' => 'A psychological drama exploring morality and redemption through the story of Rodion Raskolnikov.', 'isbn' => '978-0140449136'],
            ['id' => 2, 'title' => 'The Brothers Karamazov', 'year' => 2024, 'description' => 'A philosophical novel examining faith, doubt, and morality through the story of three brothers.', 'isbn' => '978-0374528379'],
            ['id' => 3, 'title' => 'The Idiot', 'year' => 2024, 'description' => 'The story of Prince Myshkin, a Christ-like figure in 19th century Russian society.', 'isbn' => '978-0375702245'],
            ['id' => 4, 'title' => 'War and Peace', 'year' => 2025, 'description' => 'An epic tale of Russian society during the Napoleonic era.', 'isbn' => '978-0307266934'],
            ['id' => 5, 'title' => 'Anna Karenina', 'year' => 2025, 'description' => 'A tragic love story set against the backdrop of Russian high society.', 'isbn' => '978-0143035008'],
            ['id' => 6, 'title' => 'The Death of Ivan Ilyich', 'year' => 2025, 'description' => 'A novella about a man confronting his mortality.', 'isbn' => '978-1853262616'],
            ['id' => 7, 'title' => 'The Cherry Orchard', 'year' => 2026, 'description' => 'A play about the decline of the Russian aristocracy.', 'isbn' => '978-0802130907'],
            ['id' => 8, 'title' => 'Uncle Vanya', 'year' => 2026, 'description' => 'A tragicomedy about rural Russian life.', 'isbn' => '978-0802150417'],
            ['id' => 9, 'title' => 'The Lady with the Dog', 'year' => 2026, 'description' => 'A short story about an illicit love affair.', 'isbn' => '978-1847494535'],
            ['id' => 10, 'title' => 'Eugene Onegin', 'year' => 2026, 'description' => 'A novel in verse about love and regret.', 'isbn' => '978-0140448108'],
            ['id' => 11, 'title' => 'The Queen of Spades', 'year' => 2026, 'description' => 'A short story about gambling and obsession.', 'isbn' => '978-1853261886'],
            ['id' => 12, 'title' => 'Fathers and Sons', 'year' => 2026, 'description' => 'A novel about generational conflict in 19th century Russia.', 'isbn' => '978-0140441475'],
            ['id' => 13, 'title' => 'The Master and Margarita', 'year' => 2026, 'description' => 'A satirical fantasy novel set in Soviet Moscow.', 'isbn' => '978-0140455465'],
            ['id' => 14, 'title' => 'Dead Souls', 'year' => 2026, 'description' => 'A satirical novel about a con man in imperial Russia.', 'isbn' => '978-0140448071'],
            ['id' => 15, 'title' => 'Lolita', 'year' => 2026, 'description' => 'A controversial novel about obsession and manipulation.', 'isbn' => '978-0679723165'],
            ['id' => 16, 'title' => 'Doctor Zhivago', 'year' => 2026, 'description' => 'A novel about love and revolution in early 20th century Russia.', 'isbn' => '978-0307390950'],
            ['id' => 17, 'title' => 'The Lower Depths', 'year' => 2026, 'description' => 'A play about life at the bottom of Russian society.', 'isbn' => '978-1420951059'],
        ]);

        $b->insertBatch('book_author', [
            ['book_id' => 1, 'author_id' => 1],
            ['book_id' => 2, 'author_id' => 1],
            ['book_id' => 3, 'author_id' => 1],
            ['book_id' => 4, 'author_id' => 2],
            ['book_id' => 5, 'author_id' => 2],
            ['book_id' => 6, 'author_id' => 2],
            ['book_id' => 7, 'author_id' => 3],
            ['book_id' => 8, 'author_id' => 3],
            ['book_id' => 9, 'author_id' => 3],
            ['book_id' => 10, 'author_id' => 4],
            ['book_id' => 11, 'author_id' => 4],
            ['book_id' => 12, 'author_id' => 5],
            ['book_id' => 13, 'author_id' => 6],
            ['book_id' => 14, 'author_id' => 7],
            ['book_id' => 15, 'author_id' => 8],
            ['book_id' => 16, 'author_id' => 9],
            ['book_id' => 17, 'author_id' => 10],
        ]);

        $b->insertBatch('subscription', [
            ['author_id' => 1, 'phone' => '+79001234567'],
            ['author_id' => 1, 'phone' => '+79001234568'],
            ['author_id' => 2, 'phone' => '+79001234569'],
            ['author_id' => 3, 'phone' => '+79001234570'],
            ['author_id' => 4, 'phone' => '+79001234571'],
        ]);

        // Explicit ids were inserted above, so the SERIAL sequences need to catch up before
        // the app can create new rows without colliding with them.
        foreach (['author', 'book', 'subscription'] as $table) {
            $b->execute(
                "SELECT setval(pg_get_serial_sequence('{$table}', 'id'), COALESCE((SELECT MAX(id) FROM {$table}), 0) + 1, false)",
            );
        }
    }

    public function down(MigrationBuilder $b): void
    {
        $b->truncateTable('subscription');
        $b->truncateTable('book_author');
        $b->truncateTable('book');
        $b->truncateTable('author');
    }
}
