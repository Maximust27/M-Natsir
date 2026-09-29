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
