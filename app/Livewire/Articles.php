<?php

namespace App\Livewire;

use App\Support\Content\ArticleCatalog;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts::app')]
#[Title('Artikel & Analisis | M. Natsir Kongah')]
class Articles extends Component
{
    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'category', except: 'all')]
    public string $activeCategory = 'all';

    #[Url(as: 'page', except: 1)]
    public int $page = 1;

    private int $perPage = 5;

    public function updatedSearch(): void
    {
        $this->page = 1;
    }

    public function selectCategory(string $category): void
    {
        if (! array_key_exists($category, $this->categories())) {
            return;
        }

        $this->activeCategory = $category;
        $this->page = 1;
    }

    public function goToPage(int $page): void
    {
        $this->page = max(1, min($page, $this->totalPages()));
    }

    public function nextPage(): void
    {
        $this->goToPage($this->page + 1);
    }

    public function previousPage(): void
    {
        $this->goToPage($this->page - 1);
    }

    public function render(): View
    {
        $filteredArticles = $this->filteredArticles();
        $total = count($filteredArticles);
        $totalPages = max(1, (int) ceil($total / $this->perPage));

        $this->page = max(1, min($this->page, $totalPages));

        $offset = ($this->page - 1) * $this->perPage;
        $visibleArticles = array_slice($filteredArticles, $offset, $this->perPage);

        return view('livewire.articles', [
            'categories' => $this->categories(),
            'articles' => $visibleArticles,
            'resultCount' => $total,
            'totalPages' => $totalPages,
            'resultFrom' => $total === 0 ? 0 : $offset + 1,
            'resultTo' => min($offset + $this->perPage, $total),
        ]);
    }

    /**
     * @return array<string, array{label: string, classes: string, count: int}>
     */
    private function categories(): array
    {
        $articles = ArticleCatalog::all();

        $definitions = [
            'all' => [
                'label' => 'Semua',
                'classes' => 'bg-slate-100 text-slate-700 hover:bg-slate-200',
            ],
            'pml' => [
                'label' => '★ Professional Money Laundering',
                'classes' => 'bg-amber-50 text-amber-900 hover:bg-amber-100',
            ],
            'aml' => [
                'label' => 'AML Regs',
                'classes' => 'bg-indigo-50 text-indigo-800 hover:bg-indigo-100',
            ],
            'financial-intelligence' => [
                'label' => 'Financial Intelligence',
                'classes' => 'bg-teal-50 text-teal-800 hover:bg-teal-100',
            ],
            'white-collar' => [
                'label' => 'White-Collar Crime',
                'classes' => 'bg-rose-50 text-rose-800 hover:bg-rose-100',
            ],
            'future-crime' => [
                'label' => 'Future Financial Crime',
                'classes' => 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100',
            ],
            'campaign-finance' => [
                'label' => 'Campaign Finance',
                'classes' => 'bg-violet-50 text-violet-800 hover:bg-violet-100',
            ],
        ];

        foreach ($definitions as $key => &$definition) {
            $definition['count'] = $key === 'all'
                ? count($articles)
                : count(array_filter($articles, fn (array $article): bool => $article['category'] === $key));
        }

        unset($definition);

        return $definitions;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function filteredArticles(): array
    {
        $articles = ArticleCatalog::all();
        $category = array_key_exists($this->activeCategory, $this->categories())
            ? $this->activeCategory
            : 'all';

        if ($category !== 'all') {
            $articles = array_values(array_filter(
                $articles,
                fn (array $article): bool => $article['category'] === $category,
            ));
        }

        $search = mb_strtolower(trim($this->search));

        if ($search === '') {
            return $articles;
        }

        return array_values(array_filter($articles, function (array $article) use ($search): bool {
            $haystack = mb_strtolower(implode(' ', array_filter([
                $article['title'],
                $article['excerpt'],
                $article['source'],
                $article['categoryLabel'],
                $article['accessLabel'] ?? null,
                $article['sourceNote'] ?? null,
            ])));

            return str_contains($haystack, $search);
        }));
    }

    private function totalPages(): int
    {
        return max(1, (int) ceil(count($this->filteredArticles()) / $this->perPage));
    }

}
