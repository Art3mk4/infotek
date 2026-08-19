<?php

declare(strict_types=1);

namespace App\Repository;

use App\Domain\Author;
use App\Domain\AuthorRepositoryInterface;

/**
 * Author repository
 */
final class AuthorRepository implements AuthorRepositoryInterface
{
    public function findAll(int $limit = 50, int $offset = 0): array
    {
        return Author::find()
            ->orderBy(['full_name' => SORT_ASC])
            ->limit($limit)
            ->offset($offset)
            ->all();
    }

    public function findById(int $id): ?Author
    {
        return Author::findByPk($id);
    }

    public function findByIdWithRelations(int $id): ?Author
    {
        return Author::find()->where(['id' => $id])->with('books', 'subscriptions')->one();
    }

    public function create(Author $author): Author
    {
        $author->insert();

        return $author;
    }

    public function update(Author $author): bool
    {
        return $author->update() > 0;
    }

    public function delete(int $id): bool
    {
        return (new Author())->deleteAll(['id' => $id]) > 0;
    }

    public function count(): int
    {
        return (int)Author::find()->count();
    }

    public function top10ByYear(int $year): array
    {
        return Author::find()
            ->select(['author.id', 'author.full_name', 'COUNT(book.id) AS books_count'])
            ->innerJoin('book_author', 'book_author.author_id = author.id')
            ->innerJoin('book', 'book.id = book_author.book_id')
            ->where(['book.year' => $year])
            ->groupBy(['author.id', 'author.full_name'])
            ->orderBy(['books_count' => SORT_DESC])
            ->limit(10)
            ->asArray()
            ->all();
    }
}
