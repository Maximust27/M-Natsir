<?php

namespace App\Livewire;

use App\Support\Content\TopicCatalog;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::app')]
class Topic extends Component
{
    public string $slug = '';

    public function mount(string $slug): void
    {
        abort_unless(TopicCatalog::find($slug) !== null, 404);

        $this->slug = $slug;
    }

    public function render(): View
    {
        $topic = TopicCatalog::find($this->slug);
        abort_unless($topic !== null, 404);

        return view('livewire.topic', [
            'topic' => $topic,
            'articles' => TopicCatalog::articlesFor($this->slug),
            'relatedTopics' => TopicCatalog::relatedTo($this->slug),
        ])->title($topic['seoTitle']);
    }
}
