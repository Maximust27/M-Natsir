<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::app')]
#[Title('Media & Appearances | M. Natsir Kongah')]
class Media extends Component
{
    public function render(): View
    {
        return view('livewire.media');
    }
}
