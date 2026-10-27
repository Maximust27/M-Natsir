<?php

use App\Support\Media\HeroImages;

it('uses the correct source image formats and file extensions', function () {
    $home = getimagesize(public_path('images/home-hero.jpeg'));
    $about = getimagesize(public_path('images/about-hero.png'));

    expect($home[2])->toBe(IMAGETYPE_JPEG)
        ->and($about[2])->toBe(IMAGETYPE_PNG);
});

it('keeps the LCP hero image eager and high priority on Home and Tentang', function () {
    $home = $this->get(route('home'))->assertOk()->getContent();
    $about = $this->get(route('about'))->assertOk()->getContent();

    foreach ([$home, $about] as $html) {
        expect($html)->toContain('<picture')
            ->toContain('loading="eager"')
            ->toContain('decoding="async"')
            ->toContain('fetchpriority="high"');
    }

    expect($home)->toContain('src="'.asset('images/home-hero.jpeg').'"')
        ->toContain('width="2730"')
        ->toContain('height="1536"')
        ->and($about)->toContain('src="'.asset('images/about-hero.png').'"')
        ->toContain('width="1087"')
        ->toContain('height="1447"')
        ->toContain('aspect-[3/4]');
});

it('only exposes WebP srcsets when optimized image files exist', function (string $name, string $routeName) {
    $srcset = HeroImages::webpSrcset($name);
    $html = $this->get(route($routeName))->assertOk()->getContent();

    if ($srcset === '') {
        expect($html)->not->toContain('type="image/webp"');
    } else {
        expect($html)->toContain('type="image/webp"')
            ->toContain('srcset="'.e($srcset).'"');

        foreach (explode(', ', $srcset) as $source) {
            [$url, $descriptor] = explode(' ', $source);

            expect($url)->toStartWith(asset('images/'))
                ->and($descriptor)->toMatch('/^[0-9]+w$/');

            $relativePath = parse_url($url, PHP_URL_PATH);
            expect(is_file(public_path(ltrim($relativePath, '/'))))->toBeTrue();
        }
    }
})->with([
    'home' => ['home-hero', 'home'],
    'about' => ['about-hero', 'about'],
]);

it('does not output invalid responsive sources for unknown images', function () {
    expect(HeroImages::webpSrcset('../.env'))->toBe('')
        ->and(HeroImages::webpSrcset('nonexistent'))->toBe('');
});

it('preserves page-level SEO metadata after changing hero image markup', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('<link rel="canonical" href="'.route('home').'">', false)
        ->assertSee('application/ld+json');

    $this->get(route('about'))
        ->assertOk()
        ->assertSee('<link rel="canonical" href="'.route('about').'">', false)
        ->assertSee('application/ld+json');
});
