<?php

namespace App\Livewire\Admin;

use App\Models\Article;
use App\Models\LibraryResource;
use App\Models\MediaAppearance;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;

class ContentEntry extends Component
{
    use WithFileUploads;

    private const PAGE_SIZE = 8;

    private const MODULES = [
        'articles' => ['number' => '01', 'title' => 'Artikel', 'description' => 'Tulisan, opini, dan referensi publikasi asli.'],
        'library' => ['number' => '02', 'title' => 'Library', 'description' => 'Karya, laporan, regulasi, dan pustaka riset.'],
        'media' => ['number' => '03', 'title' => 'Media', 'description' => 'Wawancara, forum kasus, dan liputan kejahatan keuangan.'],
    ];

    private const ARTICLE_CATEGORIES = [
        'pml' => ['label' => '★ Professional Money Laundering', 'badge' => 'bg-amber-100 text-amber-900'],
        'aml' => ['label' => 'AML Regs', 'badge' => 'bg-indigo-100 text-indigo-900'],
        'financial-intelligence' => ['label' => 'Financial Intelligence', 'badge' => 'bg-teal-100 text-teal-900'],
        'white-collar' => ['label' => 'White-Collar Crime', 'badge' => 'bg-rose-100 text-rose-900'],
        'future-crime' => ['label' => 'Future Financial Crime', 'badge' => 'bg-emerald-100 text-emerald-900'],
        'campaign-finance' => ['label' => 'Campaign Finance', 'badge' => 'bg-violet-100 text-violet-900'],
    ];

    private const COLLECTIONS = [
        'natsir' => 'Karya M. Natsir',
        'institutional' => 'Kontribusi Institusional',
        'reference' => 'Reference Source',
    ];

    private const LIBRARY_TYPES = [
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

    private const INSTITUTIONS = [
        'academia' => 'Academia.edu',
        'bumn-watch' => 'BUMN Watch',
        'forkamas' => 'Forkamas Gathering',
        'universitas-riau' => 'Universitas Riau',
        'ppatk' => 'PPATK',
        'fatf' => 'FATF',
        'star' => 'StAR / World Bank',
        'jdih-bpk' => 'JDIH BPK',
        'other' => 'Institusi lain (isi sendiri)',
    ];

    private const LIBRARY_TOPICS = [
        'pml' => 'Professional Money Laundering',
        'beneficial-ownership' => 'Beneficial Ownership',
        'digital-finance' => 'Digital Finance & Crypto',
        'risk-assessment' => 'Risk Assessment',
        'asset-recovery' => 'Asset Recovery',
        'aml-framework' => 'AML Framework / Pencucian Uang',
        'banking' => 'Perbankan',
        'forestry' => 'Kejahatan Kehutanan',
        'campaign-finance' => 'Campaign Finance',
        'indonesia' => 'Indonesia',
    ];

    private const MEDIA_CATEGORIES = [
        'interviews' => ['label' => 'Wawancara & Siaran', 'publicLabel' => 'Interview & Broadcast', 'badge' => 'indigo'],
        'talks' => ['label' => 'Diskusi & Seminar Kasus', 'publicLabel' => 'Talk & Panel', 'badge' => 'teal'],
        'case-coverage' => ['label' => 'Liputan / Penanganan Kasus', 'publicLabel' => 'Case Coverage', 'badge' => 'amber'],
    ];

    #[Locked]
    public string $selected = '';

    #[Locked]
    public ?int $editingId = null;

    #[Locked]
    public ?int $viewingId = null;

    #[Locked]
    public ?int $pendingDeleteId = null;

    /** @var array<string, mixed> */
    public array $entry = [];

    public string $statusFilter = 'all';

    public string $search = '';

    public int $page = 1;

    public bool $removeThumbnail = false;

    public $thumbnail = null;

    public ?string $notice = null;

    public function mount(): void
    {
        $this->assertAdmin();
    }

    public function selectModule(string $module): void
    {
        $this->assertAdmin();

        if (! array_key_exists($module, self::MODULES)) {
            return;
        }

        $this->selected = $module;
        $this->resetEditor();
        $this->viewingId = null;
        $this->pendingDeleteId = null;
        $this->statusFilter = 'all';
        $this->search = '';
        $this->page = 1;
        $this->notice = null;
    }

    public function save(): void
    {
        $this->assertAdmin();

        if (! array_key_exists($this->selected, self::MODULES)) {
            abort(422);
        }

        $validated = $this->validate($this->rules())['entry'];
        $date = filled($validated['date'] ?? null)
            ? Carbon::createFromFormat('Y-m-d', $validated['date'])->locale('id')
            : null;
        $editing = $this->editingId !== null ? $this->recordQuery()->findOrFail($this->editingId) : null;
        $oldThumbnail = $editing instanceof MediaAppearance ? $editing->thumbnail_url : null;
        $uploadedPath = null;

        if ($this->selected === 'media' && $this->thumbnail !== null) {
            $uploadedPath = $this->thumbnail->store('media/thumbnails', 'public');
        }

        try {
            DB::transaction(function () use ($validated, $date, $editing, $uploadedPath): void {
                $record = match ($this->selected) {
                    'articles' => $this->articleRecord($validated, $date, $editing instanceof Article ? $editing : null),
                    'library' => $this->libraryRecord($validated, $editing instanceof LibraryResource ? $editing : null),
                    'media' => $this->mediaRecord($validated, $date, $editing instanceof MediaAppearance ? $editing : null, $uploadedPath),
                };

                if ($editing !== null) {
                    DB::table($this->tableName())
                        ->where('id', $editing->getKey())
                        ->whereNull('deleted_at')
                        ->update($record + [
                            'is_published' => $validated['is_published'],
                            'updated_at' => now(),
                        ]);

                    return;
                }

                DB::table($this->tableName())->increment('sort_order');
                DB::table($this->tableName())->insert($record + [
                    'seed_key' => hash('sha256', 'admin:'.Str::uuid()),
                    'is_published' => $validated['is_published'],
                    'sort_order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });
        } catch (\Throwable $exception) {
            if ($uploadedPath !== null) {
                Storage::disk('public')->delete($uploadedPath);
            }

            throw $exception;
        }

        if ($editing instanceof MediaAppearance && $oldThumbnail !== null) {
            $newThumbnail = $this->recordQuery()->findOrFail($editing->getKey())->thumbnail_url;

            if ($newThumbnail !== $oldThumbnail) {
                $this->deleteManagedThumbnail($oldThumbnail);
            }
        }

        $module = self::MODULES[$this->selected]['title'];
        $status = $validated['is_published'] ? 'dipublikasikan' : 'disimpan sebagai draf';
        $operation = $editing !== null ? 'diperbarui dan ' : '';

        $this->resetEditor();
        $this->notice = "Data {$module} berhasil {$operation}{$status}.";
    }

    /**
     * @return array<string, mixed>
     */
    private function defaults(string $module): array
    {
        $common = [
            'title' => '',
            'url' => '',
            'is_published' => false,
            'source_note' => '',
        ];

        return $common + match ($module) {
            'articles' => [
                'category' => 'financial-intelligence',
                'date' => now()->toDateString(),
                'source' => '',
                'excerpt' => '',
                'is_primary_source' => true,
            ],
            'library' => [
                'collection' => 'natsir',
                'type' => 'archived-writing',
                'institution' => 'academia',
                'institution_other' => '',
                'year' => (string) now()->year,
                'topics' => [],
                'author' => '',
                'role' => '',
                'summary' => '',
                'language' => 'ID',
            ],
            'media' => [
                'category' => 'interviews',
                'date' => now()->toDateString(),
                'outlet' => '',
                'role' => '',
                'summary' => '',
                'topics_text' => '',
                'thumbnail_url' => '',
            ],
        };
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function rules(): array
    {
        $common = [
            'entry.title' => ['required', 'string', 'min:5', 'max:500'],
            'entry.url' => ['required', 'url:http,https', 'max:2000'],
            'entry.is_published' => ['required', 'boolean'],
            'entry.source_note' => ['nullable', 'string', 'max:3000'],
        ];

        return $common + match ($this->selected) {
            'articles' => [
                'entry.category' => ['required', Rule::in(array_keys(self::ARTICLE_CATEGORIES))],
                'entry.date' => ['required', 'date_format:Y-m-d'],
                'entry.source' => ['required', 'string', 'max:255'],
                'entry.excerpt' => ['required', 'string', 'min:15', 'max:5000'],
                'entry.is_primary_source' => ['required', 'boolean'],
            ],
            'library' => [
                'entry.collection' => ['required', Rule::in(array_keys(self::COLLECTIONS))],
                'entry.type' => ['required', Rule::in(array_keys(self::LIBRARY_TYPES))],
                'entry.institution' => ['required', Rule::in(array_keys(self::INSTITUTIONS))],
                'entry.institution_other' => ($this->entry['institution'] ?? null) === 'other'
                    ? ['required', 'string', 'min:2', 'max:255']
                    : ['nullable', 'string', 'max:255'],
                'entry.year' => ['required', 'regex:/^(?:[0-9]{4}|Arsip)$/'],
                'entry.topics' => ['required', 'array', 'min:1', 'max:10'],
                'entry.topics.*' => [Rule::in(array_keys(self::LIBRARY_TOPICS))],
                'entry.author' => ['nullable', 'string', 'max:255'],
                'entry.role' => ['nullable', 'string', 'max:255'],
                'entry.summary' => ['required', 'string', 'min:15', 'max:5000'],
                'entry.language' => ['required', Rule::in(['ID', 'EN', 'EN / ID'])],
            ],
            'media' => [
                'entry.category' => ['required', Rule::in(array_keys(self::MEDIA_CATEGORIES))],
                'entry.date' => ['required', 'date_format:Y-m-d'],
                'entry.outlet' => ['required', 'string', 'max:255'],
                'entry.role' => ['required', 'string', 'max:255'],
                'entry.summary' => ['required', 'string', 'min:15', 'max:5000'],
                'entry.topics_text' => ['nullable', 'string', 'max:500'],
                'entry.thumbnail_url' => ['nullable', 'url:http,https', 'max:2000'],
                'thumbnail' => ['nullable', 'image', 'max:2048'],
            ],
        };
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function articleRecord(array $data, Carbon $date): array
    {
        $category = self::ARTICLE_CATEGORIES[$data['category']];

        return [
            'category' => $data['category'],
            'category_label' => $category['label'],
            'badge_classes' => $category['badge'],
            'source' => trim($data['source']),
            'date_label' => $date->translatedFormat('j F Y'),
            'title' => trim($data['title']),
            'excerpt' => trim($data['excerpt']),
            'url' => trim($data['url']),
            'access_label' => $data['is_primary_source'] ? 'Baca Publikasi Asli' : 'Lihat Referensi',
            'is_primary_source' => $data['is_primary_source'],
            'source_note' => $data['source_note'] ?: null,
        ];
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function libraryRecord(array $data): array
    {
        $institution = $data['institution'];
        $institutionLabel = $institution === 'other'
            ? trim($data['institution_other'])
            : self::INSTITUTIONS[$institution];
        $institution = $institution === 'other'
            ? (Str::substr(Str::slug($institutionLabel), 0, 100) ?: 'institusi-lain')
            : $institution;
        $topics = array_values(array_unique($data['topics']));

        return [
            'collection' => $data['collection'],
            'collection_label' => self::COLLECTIONS[$data['collection']],
            'title' => trim($data['title']),
            'document_type' => $data['type'],
            'type_label' => self::LIBRARY_TYPES[$data['type']],
            'institution' => $institution,
            'institution_label' => $institutionLabel,
            'year_label' => $data['year'],
            'topics' => json_encode($topics, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            'topic_labels' => json_encode(
                array_map(static fn (string $topic): string => self::LIBRARY_TOPICS[$topic], $topics),
                JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE,
            ),
            'author_label' => $data['author'] ?: null,
            'role_label' => $data['role'] ?: null,
            'summary' => trim($data['summary']),
            'url' => trim($data['url']),
            'language' => $data['language'],
            'access_label' => 'Buka Sumber',
            'source_note' => $data['source_note'] ?: null,
        ];
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function mediaRecord(array $data, Carbon $date): array
    {
        $category = self::MEDIA_CATEGORIES[$data['category']];
        $topics = array_values(array_unique(array_filter(array_map(
            'trim',
            explode(',', $data['topics_text'] ?? ''),
        ))));

        $thumbnailUrl = $data['thumbnail_url'] ?: null;

        if ($this->thumbnail !== null) {
            // Uploaded files are served from public storage after storage:link.
            $thumbnailUrl = '/storage/'.$this->thumbnail->store('media/thumbnails', 'public');
        }

        return [
            'category' => $data['category'],
            'category_label' => $category['publicLabel'],
            'badge_variant' => $category['badge'],
            'date_label' => $date->translatedFormat('j F Y'),
            'year' => (int) $date->year,
            'outlet' => trim($data['outlet']),
            'role' => trim($data['role']),
            'title' => trim($data['title']),
            'summary' => trim($data['summary']),
            'topics' => json_encode($topics, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
            'url' => trim($data['url']),
            'action_label' => match ($data['category']) {
                'interviews' => 'Lihat Wawancara',
                'talks' => 'Lihat Dokumentasi',
                'case-coverage' => 'Baca Liputan',
            },
            'thumbnail_url' => $thumbnailUrl,
            'source_note' => $data['source_note'] ?: null,
        ];
    }

    private function assertAdmin(): void
    {
        abort_unless(auth()->user()?->is_admin === true, 403);
    }

    public function render(): View
    {
        $this->assertAdmin();

        return view('livewire.admin.content-entry', [
            'modules' => self::MODULES,
            'counts' => [
                'articles' => [
                    'published' => Article::query()->where('is_published', true)->count(),
                    'draft' => Article::query()->where('is_published', false)->count(),
                ],
                'library' => [
                    'published' => LibraryResource::query()->where('is_published', true)->count(),
                    'draft' => LibraryResource::query()->where('is_published', false)->count(),
                ],
                'media' => [
                    'published' => MediaAppearance::query()->where('is_published', true)->count(),
                    'draft' => MediaAppearance::query()->where('is_published', false)->count(),
                ],
            ],
            'articleCategories' => self::ARTICLE_CATEGORIES,
            'libraryCollections' => self::COLLECTIONS,
            'libraryTypes' => self::LIBRARY_TYPES,
            'libraryInstitutions' => self::INSTITUTIONS,
            'libraryTopics' => self::LIBRARY_TOPICS,
            'mediaCategories' => self::MEDIA_CATEGORIES,
        ]);
    }
}
