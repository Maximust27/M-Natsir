<?php

namespace App\Livewire;

use App\Models\LibraryResource;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts::app')]
#[Title('Referensi Intelijen Keuangan | M. Natsir Kongah')]
class Library extends Component
{
    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'collection', except: 'all')]
    public string $collection = 'all';

    #[Url(as: 'type', except: 'all')]
    public string $resourceType = 'all';

    #[Url(as: 'institution', except: 'all')]
    public string $institution = 'all';

    #[Url(as: 'topic', except: 'all')]
    public string $topic = 'all';

    public function updatedCollection(string $value): void
    {
        if (! array_key_exists($value, $this->collections())) {
            $this->collection = 'all';
        }
    }

    public function updatedResourceType(string $value): void
    {
        if (! array_key_exists($value, $this->resourceTypes())) {
            $this->resourceType = 'all';
        }
    }

    public function updatedInstitution(string $value): void
    {
        if (! array_key_exists($value, $this->institutions())) {
            $this->institution = 'all';
        }
    }

    public function updatedTopic(string $value): void
    {
        if (! array_key_exists($value, $this->topics())) {
            $this->topic = 'all';
        }
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->collection = 'all';
        $this->resourceType = 'all';
        $this->institution = 'all';
        $this->topic = 'all';
    }

    public function render(): View
    {
        $resources = $this->resources();

        return view('livewire.library', [
            'collections' => $this->collections(),
            'resourceTypes' => $this->resourceTypes(),
            'institutions' => $this->institutions(),
            'topics' => $this->topics(),
            'resources' => $this->filteredResources(),
            'totalResources' => count($resources),
            'natsirWorksCount' => count(array_filter($resources, fn (array $resource): bool => $resource['collection'] === 'natsir')),
            'institutionalCount' => count(array_filter($resources, fn (array $resource): bool => $resource['collection'] === 'institutional')),
            'referenceCount' => count(array_filter($resources, fn (array $resource): bool => $resource['collection'] === 'reference')),
        ]);
    }

    /**
     * @return array<string, array{label: string, count: int, classes: string}>
     */
    private function collections(): array
    {
        $resources = $this->resources();

        $definitions = [
            'all' => [
                'label' => 'Semua koleksi',
                'classes' => 'bg-slate-100 text-slate-700 hover:bg-slate-200',
            ],
            'natsir' => [
                'label' => 'Karya M. Natsir Kongah',
                'classes' => 'bg-amber-50 text-amber-900 hover:bg-amber-100',
            ],
            'institutional' => [
                'label' => 'Kontribusi Institusional',
                'classes' => 'bg-teal-50 text-teal-800 hover:bg-teal-100',
            ],
            'reference' => [
                'label' => 'Reference Sources',
                'classes' => 'bg-indigo-50 text-indigo-800 hover:bg-indigo-100',
            ],
        ];

        foreach ($definitions as $key => &$definition) {
            $definition['count'] = $key === 'all'
                ? count($resources)
                : count(array_filter($resources, fn (array $resource): bool => $resource['collection'] === $key));
        }

        unset($definition);

        return $definitions;
    }

    /**
     * @return array<string, string>
     */
    private function resourceTypes(): array
    {
        return [
            'all' => 'Semua jenis',
            'archived-writing' => 'Tulisan Arsip',
            'paper' => 'Makalah / Paper',
            'bibliographic-record' => 'Jejak Bibliografi',
            'institutional-report' => 'Laporan Institusional',
            'standard' => 'Standar',
            'typology' => 'Typology & Methods',
            'guidance' => 'Guidance',
            'risk-assessment' => 'Risk Assessment',
            'handbook' => 'Handbook',
            'legislation' => 'Regulasi',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function institutions(): array
    {
        return [
            'all' => 'Semua institusi / sumber',
            'academia' => 'Academia.edu',
            'bumn-watch' => 'BUMN Watch',
            'forkamas' => 'Forkamas Gathering',
            'universitas-riau' => 'Universitas Riau',
            'ppatk' => 'PPATK',
            'fatf' => 'FATF',
            'star' => 'StAR / World Bank',
            'jdih-bpk' => 'JDIH BPK',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function topics(): array
    {
        return [
            'all' => 'Semua topik',
            'pml' => 'Professional Money Laundering',
            'beneficial-ownership' => 'Beneficial Ownership',
            'digital-finance' => 'Digital Finance & Crypto',
            'risk-assessment' => 'Risk Assessment',
            'asset-recovery' => 'Asset Recovery',
            'aml-framework' => 'AML Framework',
            'banking' => 'Perbankan',
            'forestry' => 'Kejahatan Kehutanan',
            'campaign-finance' => 'Campaign Finance',
            'indonesia' => 'Indonesia',
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function filteredResources(): array
    {
        $search = mb_strtolower(trim($this->search));

        return array_values(array_filter($this->resources(), function (array $resource) use ($search): bool {
            if ($this->collection !== 'all' && $resource['collection'] !== $this->collection) {
                return false;
            }

            if ($this->resourceType !== 'all' && $resource['type'] !== $this->resourceType) {
                return false;
            }

            if ($this->institution !== 'all' && $resource['institution'] !== $this->institution) {
                return false;
            }

            if ($this->topic !== 'all' && ! in_array($this->topic, $resource['topics'], true)) {
                return false;
            }

            if ($search === '') {
                return true;
            }

            $haystack = mb_strtolower(implode(' ', array_filter([
                $resource['title'],
                $resource['summary'],
                $resource['institutionLabel'],
                $resource['typeLabel'],
                $resource['authorLabel'] ?? null,
                $resource['roleLabel'] ?? null,
                $resource['sourceNote'] ?? null,
                implode(' ', $resource['topicLabels']),
                (string) $resource['year'],
            ])));

            return str_contains($haystack, $search);
        }));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function resources(): array
    {
        return LibraryResource::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->get()
            ->map(static fn (LibraryResource $row): array => $row->toCardArray())
            ->all();
    }
}
