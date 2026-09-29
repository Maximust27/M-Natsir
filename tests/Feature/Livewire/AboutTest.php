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

it('exposes verified public profile links', function () {
    Livewire::test(About::class)
        ->assertSee('LinkedIn')
        ->assertSee('PPATK / PPID')
        ->assertSee('Kumparan');
});
