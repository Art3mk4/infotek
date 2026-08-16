<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;

/**
 * Seed test data for books catalog
 * Creates authors, books, and subscriptions for testing
 */
final class M20260816000007SeedTestData implements RevertibleMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        // Insert authors
        $authors = [
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
        ];

        foreach ($authors as $author) {
            $b->insert('author', $author);
        }

        // Insert books with various years for testing report functionality
        $books = [
            // 2024 books
            [
                'title' => 'Crime and Punishment',
                'year' => 2024,
                'description' => 'A psychological drama exploring morality and redemption through the story of Rodion Raskolnikov.',
                'isbn' => '978-0140449136',
                'cover_image' => null,
            ],
            [
                'title' => 'The Brothers Karamazov',
                'year' => 2024,
                'description' => 'A philosophical novel examining faith, doubt, and morality through the story of three brothers.',
                'isbn' => '978-0374528379',
                'cover_image' => null,
            ],
            [
                'title' => 'The Idiot',
                'year' => 2024,
                'description' => 'The story of Prince Myshkin, a Christ-like figure in 19th century Russian society.',
                'isbn' => '978-0375702245',
                'cover_image' => null,
            ],
            // 2025 books
            [
                'title' => 'War and Peace',
                'year' => 2025,
                'description' => 'An epic tale of Russian society during the Napoleonic era.',
                'isbn' => '978-0307266934',
                'cover_image' => null,
            ],
            [
                'title' => 'Anna Karenina',
                'year' => 2025,
                'description' => 'A tragic love story set against the backdrop of Russian high society.',
                'isbn' => '978-0143035008',
                'cover_image' => null,
            ],
            [
                'title' => 'The Death of Ivan Ilyich',
                'year' => 2025,
                'description' => 'A novella about a man confronting his mortality.',
                'isbn' => '978-1853262616',
                'cover_image' => null,
            ],
            // 2026 books
            [
                'title' => 'The Cherry Orchard',
                'year' => 2026,
                'description' => 'A play about the decline of the Russian aristocracy.',
                'isbn' => '978-0802130907',
                'cover_image' => null,
            ],
            [
                'title' => 'Uncle Vanya',
                'year' => 2026,
                'description' => 'A tragicomedy about rural Russian life.',
                'isbn' => '978-0802150417',
                'cover_image' => null,
            ],
            [
                'title' => 'The Lady with the Dog',
                'year' => 2026,
                'description' => 'A short story about an illicit love affair.',
                'isbn' => '978-1847494535',
                'cover_image' => null,
            ],
            [
                'title' => 'Eugene Onegin',
                'year' => 2026,
                'description' => 'A novel in verse about love and regret.',
                'isbn' => '978-0140448108',
                'cover_image' => null,
            ],
            [
                'title' => 'The Queen of Spades',
                'year' => 2026,
                'description' => 'A short story about gambling and obsession.',
                'isbn' => '978-1853261886',
                'cover_image' => null,
            ],
            [
                'title' => 'Fathers and Sons',
                'year' => 2026,
                'description' => 'A novel about generational conflict in 19th century Russia.',
                'isbn' => '978-0140441475',
                'cover_image' => null,
            ],
            [
                'title' => 'The Master and Margarita',
                'year' => 2026,
                'description' => 'A satirical fantasy novel set in Soviet Moscow.',
                'isbn' => '978-0140455465',
                'cover_image' => null,
            ],
            [
                'title' => 'Dead Souls',
                'year' => 2026,
                'description' => 'A satirical novel about a con man in imperial Russia.',
                'isbn' => '978-0140448071',
                'cover_image' => null,
            ],
            [
                'title' => 'Lolita',
                'year' => 2026,
                'description' => 'A controversial novel about obsession and manipulation.',
                'isbn' => '978-0679723165',
                'cover_image' => null,
            ],
            [
                'title' => 'Doctor Zhivago',
                'year' => 2026,
                'description' => 'A novel about love and revolution in early 20th century Russia.',
                'isbn' => '978-0307390950',
                'cover_image' => null,
            ],
            [
                'title' => 'The Lower Depths',
                'year' => 2026,
                'description' => 'A play about life at the bottom of Russian society.',
                'isbn' => '978-1420951059',
                'cover_image' => null,
            ],
        ];

        foreach ($books as $book) {
            $b->insert('book', $book);
        }

        // Link books to authors (book_author table)
        $bookAuthors = [
            // Dostoevsky's books (3 books in 2024)
            ['book_id' => 1, 'author_id' => 1],
            ['book_id' => 2, 'author_id' => 1],
            ['book_id' => 3, 'author_id' => 1],

            // Tolstoy's books (3 books in 2025)
            ['book_id' => 4, 'author_id' => 2],
            ['book_id' => 5, 'author_id' => 2],
            ['book_id' => 6, 'author_id' => 2],

            // Chekhov's books (3 books in 2026)
            ['book_id' => 7, 'author_id' => 3],
            ['book_id' => 8, 'author_id' => 3],
            ['book_id' => 9, 'author_id' => 3],

            // Pushkin's books (2 books in 2026)
            ['book_id' => 10, 'author_id' => 4],
            ['book_id' => 11, 'author_id' => 4],

            // Turgenev's books (1 book in 2026)
            ['book_id' => 12, 'author_id' => 5],

            // Bulgakov's books (1 book in 2026)
            ['book_id' => 13, 'author_id' => 6],

            // Gogol's books (1 book in 2026)
            ['book_id' => 14, 'author_id' => 7],

            // Nabokov's books (1 book in 2026)
            ['book_id' => 15, 'author_id' => 8],

            // Pasternak's books (1 book in 2026)
            ['book_id' => 16, 'author_id' => 9],

            // Gorky's books (1 book in 2026)
            ['book_id' => 17, 'author_id' => 10],
        ];

        foreach ($bookAuthors as $bookAuthor) {
            $b->insert('book_author', $bookAuthor);
        }

        // Insert test subscriptions
        $subscriptions = [
            ['author_id' => 1, 'phone' => '+79001234567'],
            ['author_id' => 1, 'phone' => '+79001234568'],
            ['author_id' => 2, 'phone' => '+79001234569'],
            ['author_id' => 3, 'phone' => '+79001234570'],
            ['author_id' => 4, 'phone' => '+79001234571'],
        ];

        foreach ($subscriptions as $subscription) {
            $b->insert('subscription', $subscription);
        }
    }

    public function down(MigrationBuilder $b): void
    {
        // Delete in reverse order to respect foreign keys
        $b->delete('subscription', '1=1');
        $b->delete('book_author', '1=1');
        $b->delete('book', '1=1');
        $b->delete('author', '1=1');
    }
}
