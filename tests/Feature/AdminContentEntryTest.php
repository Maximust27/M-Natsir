<?php

use App\Livewire\Admin\ContentEntry;
use App\Livewire\Articles;
use App\Livewire\Library;
use App\Livewire\Media;
use App\Models\Article;
use App\Models\LibraryResource;
use App\Models\MediaAppearance;
use App\Models\User;
use Database\Seeders\ContentSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Livewire;

it('shows three accessible module cards and a tailored form directly below the selection', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Pilih kartu untuk mulai')
        ->assertSee('01 / MODUL')
        ->assertSee('02 / MODUL')
        ->assertSee('03 / MODUL')
        ->assertSee('13 terbit')
        ->assertSee('22 terbit')
        ->assertSee('10 terbit')
        ->assertSee('aria-pressed="false"', false)
        ->assertSee('wire:submit="save"', false);

    Livewire::test(ContentEntry::class)
        ->call('selectModule', 'media')
        ->assertSet('selected', 'media')
        ->assertSee('Tambah Media')
        ->assertSee('Media / outlet')
        ->assertSee('Unggah foto')
        ->assertDontSee('Jenis dokumen')
        ->call('selectModule', 'library')
        ->assertSet('selected', 'library')
        ->assertSee('Jenis dokumen')
        ->assertSee('Topik referensi')
        ->assertDontSee('Unggah foto')
        ->call('selectModule', 'articles')
        ->assertSet('selected', 'articles')
        ->assertSee('Ringkasan artikel')
        ->assertDontSee('Jenis dokumen');
});

it('enforces administrator authorization on Livewire entry actions', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));

    Livewire::test(ContentEntry::class)->assertStatus(403);

    $user = User::factory()->create(['is_admin' => false]);
    $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
    Livewire::test(ContentEntry::class)->assertStatus(403);
});

it('rejects invalid manual content and unsafe publication URLs', function () {
    $this->actingAs(User::factory()->create(['is_admin' => true]));

    Livewire::test(ContentEntry::class)
        ->call('selectModule', 'articles')
        ->set('entry.title', 'Bad')
        ->set('entry.url', 'javascript:alert(1)')
        ->call('save')
        ->assertHasErrors(['entry.title', 'entry.source', 'entry.excerpt', 'entry.url']);

    $this->assertDatabaseCount('articles', 13);
});

it('saves a new article as a draft by default, without publishing it prematurely', function () {
    $this->actingAs(User::factory()->create(['is_admin' => true]));

    Livewire::test(ContentEntry::class)
        ->call('selectModule', 'articles')
        ->set('entry.title', 'Analisis Pemulihan Aset Terkini')
        ->set('entry.source', 'Media Riset Kasus')
        ->set('entry.category', 'aml')
        ->set('entry.date', '2026-11-09')
        ->set('entry.excerpt', 'Mengulas penelusuran aset dan dasar hukum dalam penanganan tindak pidana keuangan.')
        ->set('entry.url', 'https://example.org/publikasi/analisis-pemulihan-aset')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSee('disimpan sebagai draf');

    $this->assertDatabaseCount('articles', 14);

    $article = Article::query()->where('title', 'Analisis Pemulihan Aset Terkini')->firstOrFail();

    expect($article->is_published)->toBeFalse()
        ->and($article->category)->toBe('aml')
        ->and($article->category_label)->toBe('AML Regs')
        ->and($article->date_label)->toBe('9 November 2026')
        ->and(strlen($article->seed_key))->toBe(64);

    Livewire::test(Articles::class)
        ->set('search', 'Analisis Pemulihan Aset Terkini')
        ->assertDontSee('Analisis Pemulihan Aset Terkini');
});

it('publishes verified article metadata with its category label and attribution', function () {
    $this->actingAs(User::factory()->create(['is_admin' => true]));

    Livewire::test(ContentEntry::class)
        ->call('selectModule', 'articles')
        ->set('entry.title', 'Jejak Dana Kasus Aset Digital')
        ->set('entry.source', 'Jurnal Kebijakan Publik')
        ->set('entry.category', 'future-crime')
        ->set('entry.date', '2026-11-09')
        ->set('entry.excerpt', 'Pembahasan terkait penanganan aset digital dan pemulihan hasil kejahatan keuangan.')
        ->set('entry.url', 'https://example.org/kasus/aset-digital')
        ->set('entry.is_primary_source', false)
        ->set('entry.source_note', 'Referensi sekunder yang memuat jejak publikasi.')
        ->set('entry.is_published', true)
        ->call('save')
        ->assertHasNoErrors()
        ->assertSee('berhasil dipublikasikan');

    $article = Article::query()->where('title', 'Jejak Dana Kasus Aset Digital')->firstOrFail();

    expect($article->is_published)->toBeTrue()
        ->and($article->is_primary_source)->toBeFalse()
        ->and($article->access_label)->toBe('Lihat Referensi');

    Livewire::test(Articles::class)
        ->set('search', 'Jejak Dana Kasus Aset Digital')
        ->assertSee('Jejak Dana Kasus Aset Digital');
});

it('creates a library record with multiple topics and a custom institution', function () {
    $this->actingAs(User::factory()->create(['is_admin' => true]));

    Livewire::test(ContentEntry::class)
        ->call('selectModule', 'library')
        ->set('entry.title', 'Referensi Pemulihan Aset Negara')
        ->set('entry.collection', 'reference')
        ->set('entry.type', 'handbook')
        ->set('entry.institution', 'other')
        ->assertSee('Nama institusi lainnya')
        ->set('entry.institution_other', 'Pusat Kajian Aset Nusantara')
        ->set('entry.year', '2025')
        ->set('entry.topics', ['asset-recovery', 'indonesia'])
        ->set('entry.summary', 'Handbook penelitian mengenai pemulihan aset dan kerja sama lintas lembaga.')
        ->set('entry.url', 'https://example.org/referensi/asset-recovery')
        ->set('entry.is_published', true)
        ->call('save')
        ->assertHasNoErrors();

    $resource = LibraryResource::query()->where('title', 'Referensi Pemulihan Aset Negara')->firstOrFail();

    expect($resource->institution)->toBe('pusat-kajian-aset-nusantara')
        ->and($resource->institution_label)->toBe('Pusat Kajian Aset Nusantara')
        ->and($resource->topics)->toBe(['asset-recovery', 'indonesia'])
        ->and($resource->topic_labels)->toBe(['Asset Recovery', 'Indonesia'])
        ->and($resource->document_type)->toBe('handbook');

    Livewire::test(Library::class)
        ->set('institution', 'pusat-kajian-aset-nusantara')
        ->assertSee('Referensi Pemulihan Aset Negara')
        ->set('topic', 'asset-recovery')
        ->assertSee('Referensi Pemulihan Aset Negara');
});

it('creates a case-focused media record with date-derived year and uploaded thumbnail', function () {
    Storage::fake('public');
    $this->actingAs(User::factory()->create(['is_admin' => true]));

    Livewire::test(ContentEntry::class)
        ->call('selectModule', 'media')
        ->set('entry.title', 'Diskusi Penanganan Kasus Transaksi Mencurigakan')
        ->set('entry.category', 'case-coverage')
        ->set('entry.date', '2026-11-09')
        ->set('entry.outlet', 'Forum Kasus Nusantara')
        ->set('entry.role', 'Narasumber analisis kasus')
        ->set('entry.summary', 'Paparan mengenai penelusuran dana dan pemeriksaan transaksi mencurigakan.')
        ->set('entry.topics_text', 'TPPU, Penelusuran Dana, TPPU')
        ->set('entry.url', 'https://example.org/wawancara/transaksi-mencurigakan')
        ->set('entry.is_published', true)
        ->set('thumbnail', UploadedFile::fake()->image('kasus.jpg', 720, 480))
        ->call('save')
        ->assertHasNoErrors()
        ->assertSee('berhasil dipublikasikan');

    $media = MediaAppearance::query()->where('title', 'Diskusi Penanganan Kasus Transaksi Mencurigakan')->firstOrFail();

    expect($media->is_published)->toBeTrue()
        ->and($media->year)->toBe(2026)
        ->and($media->date_label)->toBe('9 November 2026')
        ->and($media->category)->toBe('case-coverage')
        ->and($media->badge_variant)->toBe('amber')
        ->and($media->topics)->toBe(['TPPU', 'Penelusuran Dana'])
        ->and($media->thumbnail_url)->toStartWith('/storage/media/thumbnails/');

    Storage::disk('public')->assertExists(Str::after($media->thumbnail_url, '/storage/'));

    Livewire::test(Media::class)
        ->call('selectCategory', 'case-coverage')
        ->assertSee('Diskusi Penanganan Kasus Transaksi Mencurigakan');
});

it('does not silently reimport retired PR-only appearances into a fresh database', function () {
    $this->assertDatabaseCount('media_appearances', 10);

    expect(MediaAppearance::query()->where('category', 'media-visits')->count())->toBe(0)
        ->and(MediaAppearance::query()->where('category', 'recognition')->count())->toBe(0)
        ->and(MediaAppearance::query()->where('category', 'interviews')->count())->toBeGreaterThan(0);
});

it('unpublishes legacy seeded PR visits on re-seed without deleting them or touching manually entered media', function () {
    $title = 'Sinergi PPATK dan Pikiran Rakyat: Membangun Kesadaran Publik Akan Bahayanya Pencucian Uang';
    $url = 'https://www.ppatk.go.id/news/read/1534/sinergi-ppatk-dan-pikiran-rakyat-membangun-kesadaran-publik-akan-bahayanya-pencucian-uang.html';

    $legacy = MediaAppearance::query()->firstOrFail()->replicate();
    $legacy->seed_key = hash('sha256', $title.'|'.$url);
    $legacy->title = $title;
    $legacy->url = $url;
    $legacy->category = 'media-visits';
    $legacy->is_published = true;
    $legacy->save();

    $manual = MediaAppearance::query()->firstOrFail()->replicate();
    $manual->seed_key = hash('sha256', 'admin:'.Str::uuid());
    $manual->title = 'Analisis Kasus Ditambahkan Manual';
    $manual->category = 'case-coverage';
    $manual->is_published = true;
    $manual->save();

    $this->seed(ContentSeeder::class);

    expect($legacy->fresh()->is_published)->toBeFalse()
        ->and($manual->fresh()->is_published)->toBeTrue()
        ->and(MediaAppearance::query()->count())->toBe(12);

    $this->get(route('media'))
        ->assertDontSee('Sinergi PPATK dan Pikiran Rakyat')
        ->assertSee('Analisis Kasus Ditambahkan Manual');
});
