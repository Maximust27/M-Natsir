<?php

namespace App\Livewire;

use App\Models\MediaAppearance;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts::app')]
#[Title('Wawancara & Media | M. Natsir Kongah')]
class Media extends Component
{
    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'category', except: 'all')]
    public string $activeCategory = 'all';

    public function selectCategory(string $category): void
    {
        if (! array_key_exists($category, $this->categories())) {
            return;
        }

        $this->activeCategory = $category;
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->activeCategory = 'all';
    }

    public function render(): View
    {
        $allAppearances = $this->appearances();
        $appearances = $this->filteredAppearances();
        $featuredAppearance = $allAppearances[0] ?? null;

        if ($featuredAppearance !== null && trim($this->search) === '' && $this->activeCategory === 'all') {
            $appearances = array_values(array_filter(
                $appearances,
                fn (array $appearance): bool => $appearance['title'] !== $featuredAppearance['title'],
            ));
        }

        return view('livewire.media', [
            'categories' => $this->categories(),
            'appearances' => $appearances,
            'featuredAppearance' => $featuredAppearance,
            'resultCount' => count($appearances),
            'totalAppearances' => count($allAppearances),
            'interviewCount' => count(array_filter(
                $allAppearances,
                fn (array $appearance): bool => $appearance['category'] === 'interviews',
            )),
            'publicEventCount' => count(array_filter(
                $allAppearances,
                fn (array $appearance): bool => in_array($appearance['category'], ['talks', 'media-visits', 'recognition'], true),
            )),
            'yearSpan' => $allAppearances === []
                ? '—'
                : min(array_column($allAppearances, 'year')).'–'.max(array_column($allAppearances, 'year')),
        ]);
    }

    /**
     * @return array<string, array{label: string, classes: string, count: int}>
     */
    private function categories(): array
    {
        $appearances = $this->appearances();

        $definitions = [
            'all' => [
                'label' => 'Semua',
                'classes' => 'bg-slate-100 text-slate-700 hover:bg-slate-200',
            ],
            'interviews' => [
                'label' => 'Interviews & Broadcast',
                'classes' => 'bg-indigo-50 text-indigo-800 hover:bg-indigo-100',
            ],
            'talks' => [
                'label' => 'Talks & Panels',
                'classes' => 'bg-teal-50 text-teal-800 hover:bg-teal-100',
            ],
            'media-visits' => [
                'label' => 'Media Visits',
                'classes' => 'bg-amber-50 text-amber-900 hover:bg-amber-100',
            ],
            'recognition' => [
                'label' => 'Recognition',
                'classes' => 'bg-rose-50 text-rose-800 hover:bg-rose-100',
            ],
        ];

        foreach ($definitions as $key => &$definition) {
            $definition['count'] = $key === 'all'
                ? count($appearances)
                : count(array_filter(
                    $appearances,
                    fn (array $appearance): bool => $appearance['category'] === $key,
                ));
        }

        unset($definition);

        return $definitions;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function filteredAppearances(): array
    {
        $appearances = $this->appearances();
        $category = array_key_exists($this->activeCategory, $this->categories())
            ? $this->activeCategory
            : 'all';

        if ($category !== 'all') {
            $appearances = array_values(array_filter(
                $appearances,
                fn (array $appearance): bool => $appearance['category'] === $category,
            ));
        }

        $search = mb_strtolower(trim($this->search));

        if ($search === '') {
            return $appearances;
        }

        return array_values(array_filter($appearances, function (array $appearance) use ($search): bool {
            $haystack = mb_strtolower(implode(' ', array_filter([
                $appearance['title'],
                $appearance['outlet'],
                $appearance['role'],
                $appearance['summary'],
                $appearance['date'],
                (string) $appearance['year'],
                implode(' ', $appearance['topics']),
                $appearance['sourceNote'] ?? null,
                $appearance['actionLabel'],
            ])));

            return str_contains($haystack, $search);
        }));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function appearances(): array
    {
        return MediaAppearance::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->get()
            ->map(static fn (MediaAppearance $row): array => $row->toCardArray())
            ->all();
    }
}
