<?php

namespace App\Http\Controllers;

use App\Support\Content\TopicCatalog;
use App\Support\Seo\PageMetadata;
use Illuminate\Http\Response;

final class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $urls = array_map(
            static fn (string $routeName): string => route($routeName),
            config('seo.sitemap_routes', []),
        );

        foreach (TopicCatalog::all() as $topic) {
            $urls[] = route('topics.show', ['slug' => $topic['slug']]);
        }

        for ($page = 2; $page <= PageMetadata::articlePageCount(); $page++) {
            $urls[] = route('articles', ['page' => $page]);
        }

        return response()->view('seo.sitemap', ['urls' => $urls], 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    public function robots(): Response
    {
        $body = config('seo.allow_indexing')
            ? "User-agent: *\nAllow: /\nSitemap: ".route('sitemap')."\n"
            : "User-agent: *\nDisallow: /\n";

        return response($body, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
