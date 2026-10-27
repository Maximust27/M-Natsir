<?php

use App\Support\Content\ArticleCatalog;
use App\Support\Content\TopicCatalog;

it('serves original topic reading paths with route-specific SEO metadata and attributed sources', function (string $slug, string $articleTitle, string $sourceUrl) {
    $topic = TopicCatalog::find($slug);

    $this->get(route('topics.show', ['slug' => $slug]))
        ->assertOk()
        ->assertSee('<title>'.e($topic['seoTitle']).'</title>', false)
        ->assertSee('<meta name="description" content="'.e($topic['description']).'">', false)
        ->assertSee('<link rel="canonical" href="'.route('topics.show', ['slug' => $slug]).'">', false)
        ->assertSee('<meta property="og:url" content="'.route('topics.show', ['slug' => $slug]).'">', false)
        ->assertSee($topic['heading'])
        ->assertSee('Tiga pertanyaan untuk didalami')
        ->assertSee('Halaman ini adalah pengantar')
        ->assertSee($articleTitle)
        ->assertSee('href="'.$sourceUrl.'"', false)
        ->assertSee('Lihat referensi terkait di Library');
})->with([
    'anti pencucian uang' => ['anti-pencucian-uang', 'Batas Negara atas Harta', 'https://kumparan.com/natsir-kongah/batas-negara-atas-harta-289MtKTiAEo'],
    'pencucian uang profesional' => ['pencucian-uang-profesional', 'Dimana Uang Penipu Berhenti', 'https://majalaheditor.com/dimana-uang-penipu-berhenti/'],
    'intelijen keuangan' => ['intelijen-keuangan', 'Nilai Bergerak, Informasi Tertinggal', 'https://majalaheditor.com/nilai-bergerak-informasi-tertinggal/'],
]);

it('returns a 404 for unknown topic slugs', function () {
    $this->get(route('topics.show', ['slug' => 'topik-tidak-ada']))->assertNotFound();
});

it('indexes every curated topic in the sitemap with no duplicate or filtered URL', function () {
    $content = $this->get(route('sitemap'))->assertOk()->getContent();

    foreach (TopicCatalog::all() as $topic) {
        $url = route('topics.show', ['slug' => $topic['slug']]);

        expect(substr_count($content, '<loc>'.$url.'</loc>'))->toBe(1);
        $this->get($url.'?utm_source=test')
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.$url.'">', false);
    }

    expect($content)->not->toContain('category=')->not->toContain('q=');
});

it('links all topic hubs from the homepage, articles archive, library, and footer', function () {
    foreach (['home', 'articles', 'library'] as $routeName) {
        $html = $this->get(route($routeName))->assertOk()->getContent();

        foreach (TopicCatalog::all() as $topic) {
            expect($html)->toContain('href="'.route('topics.show', ['slug' => $topic['slug']]).'"');
        }
    }
});

it('connects each topic to related topics, the library, and its own category filter', function () {
    foreach (TopicCatalog::all() as $topic) {
        $html = $this->get(route('topics.show', ['slug' => $topic['slug']]))->assertOk()->getContent();

        expect($html)->toContain('href="'.route('library', ['topic' => $topic['libraryTopic']]).'"')
            ->toContain('href="'.route('articles', ['category' => $topic['category']]).'"');

        foreach (TopicCatalog::relatedTo($topic['slug']) as $related) {
            expect($html)->toContain('href="'.route('topics.show', ['slug' => $related['slug']]).'"');
        }
    }
});

it('derives source listings from the verified article catalog without duplicating full articles', function () {
    foreach (TopicCatalog::all() as $topic) {
        $articles = TopicCatalog::articlesFor($topic['slug']);
        $matching = array_values(array_filter(
            ArticleCatalog::all(),
            fn (array $article): bool => $article['category'] === $topic['category'],
        ));

        expect($articles)->toBe($matching)->not->toBeEmpty();

        foreach ($articles as $article) {
            expect($article['url'])->toStartWith('https://');
        }
    }
});

it('uses a WebPage schema for a reading path instead of pretending to host an original Article', function () {
    $html = $this->get(route('topics.show', ['slug' => 'pencucian-uang-profesional']))->assertOk()->getContent();

    preg_match('~<script type="application/ld\+json">(.*?)</script>~s', $html, $match);
    $data = json_decode($match[1] ?? '', true, 512, JSON_THROW_ON_ERROR);
    $webPage = collect($data['@graph'])->firstWhere('@type', 'WebPage');

    expect($webPage['url'])->toBe(route('topics.show', ['slug' => 'pencucian-uang-profesional']))
        ->and(array_column($data['@graph'], '@type'))->not->toContain('Article');
});

it('keeps indexing disabled for localhost but indexable on production for topic pages', function () {
    $url = route('topics.show', ['slug' => 'intelijen-keuangan']);

    config()->set('seo.allow_indexing', false);
    $this->get($url)->assertSee('<meta name="robots" content="noindex, follow">', false);

    config()->set('seo.allow_indexing', true);
    $this->get($url)->assertSee('<meta name="robots" content="index, follow">', false);
});
