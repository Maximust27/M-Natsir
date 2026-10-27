<?php

namespace App\Support\Seo;

use App\Livewire\Articles;
use App\Support\Content\ArticleCatalog;
use Illuminate\Http\Request;

final class PageMetadata
{
    public static function canonical(Request $request): string
    {
        $routeName = $request->route()?->getName();

        if (! is_string($routeName) || ! array_key_exists($routeName, config('seo.pages', []))) {
            return url()->current();
        }

        if ($routeName !== 'articles' || self::hasFilters($request)) {
            return route($routeName);
        }

        $page = self::requestedArticlePage($request);

        if ($page !== null && $page > 1 && $page <= self::articlePageCount()) {
            return route('articles', ['page' => $page]);
        }

        return route('articles');
    }

    public static function robots(Request $request): string
    {
        if (! config('seo.allow_indexing')) {
            return 'noindex, follow';
        }

        if (self::hasFilters($request)) {
            return 'noindex, follow';
        }

        if ($request->route()?->getName() === 'articles') {
            $page = self::requestedArticlePage($request);

            if ($page === null || $page > self::articlePageCount()) {
                return 'noindex, follow';
            }
        }

        return 'index, follow';
    }

    public static function articlePageCount(): int
    {
        return max(1, (int) ceil(count(ArticleCatalog::all()) / Articles::PER_PAGE));
    }

    private static function hasFilters(Request $request): bool
    {
        return match ($request->route()?->getName()) {
            'articles', 'media' => self::isActive($request, 'q', '')
                || self::isActive($request, 'category', 'all'),
            'library' => self::isActive($request, 'q', '')
                || self::isActive($request, 'collection', 'all')
                || self::isActive($request, 'type', 'all')
                || self::isActive($request, 'institution', 'all')
                || self::isActive($request, 'topic', 'all'),
            default => false,
        };
    }

    private static function isActive(Request $request, string $name, string $default): bool
    {
        $value = $request->query($name, $default);

        return ! is_scalar($value) || trim((string) $value) !== $default;
    }

    private static function requestedArticlePage(Request $request): ?int
    {
        $rawPage = $request->query('page', 1);

        if (! is_scalar($rawPage) || ! ctype_digit((string) $rawPage)) {
            return null;
        }

        $page = filter_var($rawPage, FILTER_VALIDATE_INT);

        return $page !== false && $page >= 1 ? $page : null;
    }
}
