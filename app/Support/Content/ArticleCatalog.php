<?php

namespace App\Support\Content;

use App\Models\Article;

final class ArticleCatalog
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function all(): array
    {
        return Article::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->get()
            ->map(static fn (Article $row): array => $row->toCardArray())
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function featured(int $limit = 3): array
    {
        return array_slice(
            array_values(array_filter(
                self::all(),
                fn (array $article): bool => ($article['isPrimarySource'] ?? true) === true,
            )),
            0,
            $limit,
        );
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function findByTitle(string $title): ?array
    {
        foreach (self::all() as $article) {
            if ($article['title'] === $title) {
                return $article;
            }
        }

        return null;
    }
}
