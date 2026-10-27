<?php

/**
 * @return array<string, mixed>
 */
function seoJsonLdFromHtml(string $html): array
{
    preg_match_all('~<script type="application/ld\+json">\s*(.*?)\s*</script>~s', $html, $matches);

    expect($matches[1])->toHaveCount(1);

    return json_decode($matches[1][0], true, 512, JSON_THROW_ON_ERROR);
}

it('describes the website and real owner on the homepage', function () {
    $response = $this->get(route('home'))->assertOk();
    $schema = seoJsonLdFromHtml($response->getContent());
    $nodes = collect($schema['@graph']);

    $website = $nodes->firstWhere('@type', 'WebSite');
    $person = $nodes->firstWhere('@type', 'Person');
    $page = $nodes->firstWhere('@type', 'WebPage');

    expect($schema['@context'])->toBe('https://schema.org')
        ->and($website['@id'])->toBe(route('home').'#website')
        ->and($website['url'])->toBe(route('home'))
        ->and($website['name'])->toBe(config('seo.site_name'))
        ->and($website['publisher']['@id'])->toBe(route('about').'#person')
        ->and($person['@id'])->toBe(route('about').'#person')
        ->and($person['name'])->toBe('M. Natsir Kongah')
        ->and($page['@id'])->toBe(route('home').'#webpage')
        ->and($page['isPartOf']['@id'])->toBe($website['@id'])
        ->and($page['about']['@id'])->toBe($person['@id']);
});

it('uses ProfilePage only for Tentang with a fully described Person', function () {
    $response = $this->get(route('about'))->assertOk();
    $schema = seoJsonLdFromHtml($response->getContent());
    $nodes = collect($schema['@graph']);
    $page = $nodes->firstWhere('@type', 'ProfilePage');
    $person = $page['mainEntity'];

    expect($nodes)->toHaveCount(1)
        ->and($page['url'])->toBe(route('about'))
        ->and($page['@id'])->toBe(route('about').'#webpage')
        ->and($person['@type'])->toBe('Person')
        ->and($person['@id'])->toBe(route('about').'#person')
        ->and($person['name'])->toBe('M. Natsir Kongah')
        ->and($person['description'])->toBe(config('seo.person.description'))
        ->and($person['image'])->toBe(asset('images/about-hero.png'))
        ->and($person['sameAs'])->toBe(config('seo.person.same_as'))
        ->and($person['knowsAbout'])->toBe(config('seo.person.knows_about'));

    foreach ($person['sameAs'] as $profileUrl) {
        $response->assertSee('href="'.$profileUrl.'"', false);
    }

    $response->assertSee('Pembelajar Anti Pencucian Uang', false);
});

it('renders one valid WebPage JSON-LD graph on other public routes', function (string $routeName) {
    $response = $this->get(route($routeName))->assertOk();
    $schema = seoJsonLdFromHtml($response->getContent());
    $nodes = $schema['@graph'];
    $page = $nodes[0];

    expect($nodes)->toHaveCount(1)
        ->and($page['@type'])->toBe('WebPage')
        ->and($page['url'])->toBe(route($routeName))
        ->and($page['@id'])->toBe(route($routeName).'#webpage')
        ->and($page['description'])->toBe(config('seo.pages.'.$routeName.'.description'))
        ->and($page['inLanguage'])->toBe('id-ID')
        ->and($page['isPartOf']['@id'])->toBe(route('home').'#website');
})->with(['articles', 'library', 'media', 'contact']);

it('uses the same canonical URL for JSON-LD on paginated and filtered pages', function () {
    foreach ([
        [route('articles', ['page' => 2]), route('articles', ['page' => 2])],
        [route('articles', ['q' => 'uang']), route('articles')],
        [route('library', ['topic' => 'asset-recovery']), route('library')],
    ] as [$requestUrl, $canonicalUrl]) {
        $response = $this->get($requestUrl)->assertOk();
        $schema = seoJsonLdFromHtml($response->getContent());
        $page = collect($schema['@graph'])->firstWhere('@type', 'WebPage');

        expect($page['url'])->toBe($canonicalUrl)
            ->and($page['@id'])->toBe($canonicalUrl.'#webpage');
        $response->assertSee('<link rel="canonical" href="'.$canonicalUrl.'">', false);
    }
});

it('does not claim externally linked catalog articles are hosted Article pages', function () {
    $schema = seoJsonLdFromHtml($this->get(route('articles'))->assertOk()->getContent());
    $types = array_column($schema['@graph'], '@type');

    expect($types)->not->toContain('Article')
        ->and($types)->not->toContain('NewsArticle')
        ->and($types)->not->toContain('BlogPosting');
});

it('escapes JSON-LD safely without permitting script tag injection', function () {
    $description = '</script><script>alert("injected")</script>';

    config()->set('seo.pages.home.description', $description);

    $html = $this->get(route('home'))->assertOk()->getContent();
    $schema = seoJsonLdFromHtml($html);
    $page = collect($schema['@graph'])->firstWhere('@type', 'WebPage');

    expect($page['description'])->toBe($description)
        ->and($html)->not->toContain('</script><script>');
});
