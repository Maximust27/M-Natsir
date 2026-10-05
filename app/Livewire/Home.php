<?php

namespace App\Livewire;

use App\Support\Content\ArticleCatalog;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::app')]
#[Title('M. Natsir Kongah | Financial Intelligence Hub')]
class Home extends Component
{
    public function render(): View
    {
        $spotlight = ArticleCatalog::findByTitle('Campaign funding: Lesson from Watergate')
            ?? ArticleCatalog::featured(1)[0];

        return view('livewire.home', [
            'featuredWritings' => ArticleCatalog::featured(3),
            'spotlight' => $spotlight,
        ]);
    }
}
