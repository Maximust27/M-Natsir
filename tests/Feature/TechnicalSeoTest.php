<?php

use App\Livewire\Articles;
use App\Support\Content\TopicCatalog;
use App\Support\Seo\PageMetadata;
use Livewire\Livewire;

it('emits a clean canonical URL for every public route', function (string $routeName) {
    $this->get(route($routeName, ['utm_source' => 'newsletter']))
        ->assertOk()
        ->assertSee('<link rel="canonical" href="'.route($routeName).'">', false)
        ->assertSee('<meta property="og:url" content="'.route($routeName).'">', false);
})->with(['home', 'about', 'articles', 'library', 'media', 'contact']);

it('self-canonicalizes valid article archive pages instead of collapsing them into page one', function () {
    config()->set('seo.allow_indexing', true);

    $this->get(route('articles', ['page' => 2]))
        ->assertOk()
        ->assertSee('<link rel="canonical" href="'.route('articles', ['page' => 2]).'">', false)
        ->assertSee('<meta name="robots" content="index, follow">', false)
        ->assertSee('href="'.route('articles', ['page' => 3]).'"', false);

    $this->get(route('articles', ['page' => 1]))
        ->assertOk()
        ->assertSee('<link rel="canonical" href="'.route('articles').'">', false);
});

it('does not index site search or filtered archives', function () {
    config()->set('seo.allow_indexing', true);

    foreach ([
        route('articles', ['q' => 'penipuan']),
        route('articles', ['category' => 'aml']),
        route('library', ['topic' => 'asset-recovery']),
        route('media', ['category' => 'interviews']),
        route('articles', ['page' => 999]),
    ] as $url) {
        $this->get($url)
            ->assertOk()
            ->assertSee('<meta name="robots" content="noindex, follow">', false);
    }
});

it('keeps the search and category in crawlable pagination links', function () {
    $url = Livewire::test(Articles::class)
        ->set('search', 'uang')
        ->set('activeCategory', 'aml')
        ->instance()
        ->paginationUrl(2);

    expect($url)->toBe(route('articles', [
        'q' => 'uang',
        'category' => 'aml',
        'page' => 2,
    ]));
});

it('publishes a valid XML sitemap of canonical public pages and article pagination', function () {
    $response = $this->get(route('sitemap'))
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
        ->assertSee('<?xml version="1.0" encoding="UTF-8"?>', false)
        ->assertSee('<loc>'.route('home').'</loc>', false)
        ->assertSee('<loc>'.route('articles', ['page' => 2]).'</loc>', false)
        ->assertDontSee('/contact/cv')
        ->assertDontSee('?q=');

    expect(substr_count($response->getContent(), '<loc>'))
        ->toBe(count(config('seo.sitemap_routes')) + count(TopicCatalog::all()) + PageMetadata::articlePageCount() - 1);
});

it('blocks robots by default and exposes the sitemap only when indexing is enabled', function () {
    config()->set('seo.allow_indexing', false);

    $this->get(route('robots'))
        ->assertOk()
        ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
        ->assertSee('Disallow: /')
        ->assertDontSee('Sitemap:');

    $this->get(route('home'))
        ->assertSee('<meta name="robots" content="noindex, follow">', false);

    config()->set('seo.allow_indexing', true);

    $this->get(route('robots'))
        ->assertOk()
        ->assertSee('Allow: /')
        ->assertSee('Sitemap: '.route('sitemap'))
        ->assertDontSee('Disallow: /');

    $this->get(route('home'))
        ->assertSee('<meta name="robots" content="index, follow">', false);
});
