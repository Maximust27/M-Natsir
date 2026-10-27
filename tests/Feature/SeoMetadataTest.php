<?php

it('renders unique page metadata and social previews for all public pages', function (string $routeName, string $title) {
    $page = config('seo.pages.'.$routeName);
    $description = $page['description'];
    $image = asset($page['image'] ?? config('seo.default_image'));
    $imageAlt = $page['image_alt'] ?? config('seo.default_image_alt');

    $this->get(route($routeName))
        ->assertOk()
        ->assertSee('<title>'.e($title).'</title>', false)
        ->assertSee('<meta name="description" content="'.e($description).'">', false)
        ->assertSee('<meta property="og:type" content="website">', false)
        ->assertSee('<meta property="og:locale" content="id_ID">', false)
        ->assertSee('<meta property="og:site_name" content="M. Natsir Kongah">', false)
        ->assertSee('<meta property="og:title" content="'.e($title).'">', false)
        ->assertSee('<meta property="og:description" content="'.e($description).'">', false)
        ->assertSee('<meta property="og:url" content="'.route($routeName).'">', false)
        ->assertSee('<meta property="og:image" content="'.e($image).'">', false)
        ->assertSee('<meta property="og:image:alt" content="'.e($imageAlt).'">', false)
        ->assertSee('<meta name="twitter:card" content="summary_large_image">', false)
        ->assertSee('<meta name="twitter:title" content="'.e($title).'">', false)
        ->assertSee('<meta name="twitter:description" content="'.e($description).'">', false)
        ->assertSee('<meta name="twitter:image" content="'.e($image).'">', false);
})->with([
    'home' => ['home', 'M. Natsir Kongah | Financial Intelligence Hub'],
    'about' => ['about', 'Profil M. Natsir Kongah | Intelijen Keuangan'],
    'articles' => ['articles', 'Artikel Anti Pencucian Uang | M. Natsir Kongah'],
    'library' => ['library', 'Referensi Intelijen Keuangan | M. Natsir Kongah'],
    'media' => ['media', 'Wawancara & Media | M. Natsir Kongah'],
    'contact' => ['contact', 'Kontak M. Natsir Kongah | Wawancara & Diskusi'],
]);

it('keeps page descriptions distinct', function () {
    $descriptions = array_column(config('seo.pages'), 'description');

    expect($descriptions)->toHaveCount(6);
    expect(array_unique($descriptions))->toHaveCount(count($descriptions));
});

it('uses the clean page URL for social previews of filtered listings', function () {
    $this->get(route('articles', ['q' => 'money']))
        ->assertOk()
        ->assertSee('<meta property="og:url" content="'.route('articles').'">', false)
        ->assertDontSee('<meta property="og:url" content="'.route('articles', ['q' => 'money']).'">', false);
});
