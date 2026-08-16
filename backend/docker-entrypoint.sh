#!/bin/bash
set -e

echo "Waiting for database to be ready..."
until PGPASSWORD=$DB_PASSWORD psql -h "$DB_HOST" -U "$DB_USER" -d "$DB_NAME" -c '\q' 2>/dev/null; do
  echo "PostgreSQL is unavailable - sleeping"
  sleep 2
done

echo "PostgreSQL is up - executing migrations with PDO"
php -r '
require "/app/vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable("/app");
$dotenv->safeLoad();

$host = $_ENV["DB_HOST"] ?? "db";
$dbname = $_ENV["DB_NAME"] ?? "books";
$user = $_ENV["DB_USER"] ?? "yii";
$password = $_ENV["DB_PASSWORD"] ?? "yiipass";

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "Running migrations...\n";

    // Migration 1: Create author table
    $pdo->exec("CREATE TABLE IF NOT EXISTS author (
        id SERIAL PRIMARY KEY,
        full_name VARCHAR(255) NOT NULL
    )");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_author_full_name ON author(full_name)");

    // Migration 2: Create book table
    $pdo->exec("CREATE TABLE IF NOT EXISTS book (
        id SERIAL PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        year INTEGER NOT NULL,
        description TEXT,
        isbn VARCHAR(20),
        cover_image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_book_title ON book(title)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_book_year ON book(year)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_book_isbn ON book(isbn)");

    // Migration 3: Create book_author table
    $pdo->exec("CREATE TABLE IF NOT EXISTS book_author (
        book_id INTEGER NOT NULL,
        author_id INTEGER NOT NULL,
        PRIMARY KEY (book_id, author_id),
        CONSTRAINT fk_book_author_book FOREIGN KEY (book_id) REFERENCES book(id) ON DELETE CASCADE,
        CONSTRAINT fk_book_author_author FOREIGN KEY (author_id) REFERENCES author(id) ON DELETE CASCADE
    )");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_book_author_book_id ON book_author(book_id)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_book_author_author_id ON book_author(author_id)");

    // Migration 4: Create user table
    $pdo->exec("CREATE TABLE IF NOT EXISTS \"user\" (
        id SERIAL PRIMARY KEY,
        username VARCHAR(100) NOT NULL UNIQUE,
        email VARCHAR(255) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        auth_key VARCHAR(32),
        status SMALLINT DEFAULT 10,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_user_username ON \"user\"(username)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_user_email ON \"user\"(email)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_user_status ON \"user\"(status)");

    // Migration 5: Create subscription table
    $pdo->exec("CREATE TABLE IF NOT EXISTS subscription (
        id SERIAL PRIMARY KEY,
        author_id INTEGER NOT NULL,
        phone VARCHAR(20) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_subscription_author FOREIGN KEY (author_id) REFERENCES author(id) ON DELETE CASCADE,
        CONSTRAINT unique_author_phone UNIQUE (author_id, phone)
    )");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_subscription_author_id ON subscription(author_id)");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_subscription_phone ON subscription(phone)");

    // Migration 6: Insert demo user
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM \"user\" WHERE username = ?");
    $stmt->execute(["admin"]);
    if ($stmt->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO \"user\" (username, email, password_hash, auth_key, status)
                    VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(["admin", "admin@example.com", "\$2y\$10\$Biw34o4ujKm3qP.1L/7BHOJVf/HiSa0irf2DZO1S5PpVlJNBKd0JK", "test-auth-key-12345678901234", 10]);
        echo "Demo user created\n";
    }

    echo "Migrations completed!\n";
} catch (Exception $e) {
    echo "Migration error: " . $e->getMessage() . "\n";
    exit(1);
}
'

# Insert demo user idempotently
PGPASSWORD=$DB_PASSWORD psql -h "$DB_HOST" -U "$DB_USER" -d "$DB_NAME" <<'EOF'
INSERT INTO "user" (username, email, password_hash, auth_key, status)
SELECT 'admin', 'admin@example.com', '$2y$10$Biw34o4ujKm3qP.1L/7BHOJVf/HiSa0irf2DZO1S5PpVlJNBKd0JK', 'test-auth-key-12345678901234', 10
WHERE NOT EXISTS (SELECT 1 FROM "user" WHERE username = 'admin');
EOF

echo "Demo user ready (admin / admin123)"

# Seed test catalog data idempotently
PGPASSWORD=$DB_PASSWORD psql -h "$DB_HOST" -U "$DB_USER" -d "$DB_NAME" <<'EOF'
DO $$
BEGIN
    IF EXISTS (SELECT 1 FROM author LIMIT 1) THEN
        RAISE NOTICE 'Seed data already present, skipping';
        RETURN;
    END IF;

    INSERT INTO author (id, full_name) VALUES
    (1, 'Fyodor Dostoevsky'),
    (2, 'Leo Tolstoy'),
    (3, 'Anton Chekhov'),
    (4, 'Alexander Pushkin'),
    (5, 'Ivan Turgenev'),
    (6, 'Mikhail Bulgakov'),
    (7, 'Nikolai Gogol'),
    (8, 'Vladimir Nabokov'),
    (9, 'Boris Pasternak'),
    (10, 'Maxim Gorky');

    INSERT INTO book (id, title, year, description, isbn, cover_image) VALUES
    (1, 'Crime and Punishment', 2024, 'A psychological drama exploring morality and redemption through the story of Rodion Raskolnikov.', '978-0140449136', null),
    (2, 'The Brothers Karamazov', 2024, 'A philosophical novel examining faith, doubt, and morality through the story of three brothers.', '978-0374528379', null),
    (3, 'The Idiot', 2024, 'The story of Prince Myshkin, a Christ-like figure in 19th century Russian society.', '978-0375702245', null),
    (4, 'War and Peace', 2025, 'An epic tale of Russian society during the Napoleonic era.', '978-0307266934', null),
    (5, 'Anna Karenina', 2025, 'A tragic love story set against the backdrop of Russian high society.', '978-0143035008', null),
    (6, 'The Death of Ivan Ilyich', 2025, 'A novella about a man confronting his mortality.', '978-1853262616', null),
    (7, 'The Cherry Orchard', 2026, 'A play about the decline of the Russian aristocracy.', '978-0802130907', null),
    (8, 'Uncle Vanya', 2026, 'A tragicomedy about rural Russian life.', '978-0802150417', null),
    (9, 'The Lady with the Dog', 2026, 'A short story about an illicit love affair.', '978-1847494535', null),
    (10, 'Eugene Onegin', 2026, 'A novel in verse about love and regret.', '978-0140448108', null),
    (11, 'The Queen of Spades', 2026, 'A short story about gambling and obsession.', '978-1853261886', null),
    (12, 'Fathers and Sons', 2026, 'A novel about generational conflict in 19th century Russia.', '978-0140441475', null),
    (13, 'The Master and Margarita', 2026, 'A satirical fantasy novel set in Soviet Moscow.', '978-0140455465', null),
    (14, 'Dead Souls', 2026, 'A satirical novel about a con man in imperial Russia.', '978-0140448071', null),
    (15, 'Lolita', 2026, 'A controversial novel about obsession and manipulation.', '978-0679723165', null),
    (16, 'Doctor Zhivago', 2026, 'A novel about love and revolution in early 20th century Russia.', '978-0307390950', null),
    (17, 'The Lower Depths', 2026, 'A play about life at the bottom of Russian society.', '978-1420951059', null);

    INSERT INTO book_author (book_id, author_id) VALUES
    (1, 1), (2, 1), (3, 1),
    (4, 2), (5, 2), (6, 2),
    (7, 3), (8, 3), (9, 3),
    (10, 4), (11, 4),
    (12, 5),
    (13, 6),
    (14, 7),
    (15, 8),
    (16, 9),
    (17, 10);

    INSERT INTO subscription (author_id, phone) VALUES
    (1, '+79001234567'),
    (1, '+79001234568'),
    (2, '+79001234569'),
    (3, '+79001234570'),
    (4, '+79001234571');

    PERFORM setval(pg_get_serial_sequence('author', 'id'), COALESCE((SELECT MAX(id) FROM author), 0) + 1, false);
    PERFORM setval(pg_get_serial_sequence('book', 'id'), COALESCE((SELECT MAX(id) FROM book), 0) + 1, false);
    PERFORM setval(pg_get_serial_sequence('subscription', 'id'), COALESCE((SELECT MAX(id) FROM subscription), 0) + 1, false);

    RAISE NOTICE 'Seed data inserted';
END $$;
EOF

echo "Starting FrankenPHP..."
exec frankenphp run --config /etc/caddy/Caddyfile
