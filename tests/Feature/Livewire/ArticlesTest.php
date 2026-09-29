<?php

use App\Livewire\Articles;
use Livewire\Livewire;

it('renders the articles archive', function () {
    $this->get(route('articles'))
        ->assertOk()
        ->assertSee('ARTIKEL & ANALISIS')
        ->assertSee('Tulisan, ringkasan opini media, dan catatan kritis seputar kejahatan keuangan.')
        ->assertSeeLivewire(Articles::class);
});

it('filters articles by category', function () {
    Livewire::test(Articles::class)
        ->call('selectCategory', 'pml')
        ->assertSet('activeCategory', 'pml')
        ->assertSee('Ketika Kejahatan Memiliki Manajer Investasi')
        ->assertDontSee('Ketika Judi Online Bertemu Kripto');
});

it('searches across article titles and summaries', function () {
    Livewire::test(Articles::class)
        ->set('search', 'judi online')
        ->assertSee('Ketika Judi Online Bertemu Kripto')
        ->assertDontSee('Batas Negara atas Harta');
});

it('ignores unknown article categories', function () {
    Livewire::test(Articles::class)
        ->call('selectCategory', 'unknown')
        ->assertSet('activeCategory', 'all');
});

it('paginates the article archive within valid bounds', function () {
    Livewire::test(Articles::class)
        ->assertSet('page', 1)
        ->call('nextPage')
        ->assertSet('page', 2)
        ->call('nextPage')
        ->assertSet('page', 2)
        ->call('previousPage')
        ->assertSet('page', 1)
        ->call('previousPage')
        ->assertSet('page', 1)
        ->call('goToPage', 99)
        ->assertSet('page', 2)
        ->call('goToPage', 0)
        ->assertSet('page', 1);
});

it('resets pagination when search or category changes', function () {
    Livewire::test(Articles::class)
        ->call('nextPage')
        ->assertSet('page', 2)
        ->set('search', 'judi online')
        ->assertSet('page', 1)
        ->call('nextPage')
        ->assertSet('page', 1)
        ->set('search', '')
        ->call('nextPage')
        ->assertSet('page', 2)
        ->call('selectCategory', 'pml')
        ->assertSet('page', 1);
});
