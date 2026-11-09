<?php

use App\Livewire\About;
use Livewire\Livewire;

it('renders the about page', function () {
    $this->get(route('about'))
        ->assertOk()
        ->assertSee('M. NATSIR KONGAH')
        ->assertSee('Perjalanan Intelektual')
        ->assertSeeLivewire(About::class);
});

it('exposes selected professional and publication links on the about page', function () {
    Livewire::test(About::class)
        ->assertSee('LinkedIn')
        ->assertSee('Download CV')
        ->assertSee('Kumparan')
        ->assertSee('Academia.edu')
        ->assertSee('Artikel Majalah Editor')
        ->assertDontSee('PPATK / PPID')
        ->assertDontSee('Buka Contact');
});
