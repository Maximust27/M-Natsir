<?php

use App\Livewire\Home;
use Livewire\Livewire;

it('renders the home page', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('M. NATSIR KONGAH')
        ->assertSeeLivewire(Home::class);
});

it('updates the active knowledge pillar', function () {
    Livewire::test(Home::class)
        ->assertSet('activePillar', 'all')
        ->call('selectPillar', 'pml')
        ->assertSet('activePillar', 'pml')
        ->call('selectPillar', 'invalid')
        ->assertSet('activePillar', 'pml');
});
