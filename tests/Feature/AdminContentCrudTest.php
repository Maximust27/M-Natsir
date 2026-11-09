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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->actingAs(User::factory()->create(['is_admin' => true]));
});

it('adds recoverable soft-delete columns to all three content tables', function () {
    foreach (['articles', 'library_resources', 'media_appearances'] as $table) {
        expect(Schema::hasColumn($table, 'deleted_at'))->toBeTrue();
    }
});

it('shows paginated content lists with status tabs, live search and detail metadata', function () {
    Livewire::test(ContentEntry::class)
        ->call('selectModule', 'articles')
        ->assertSee('Daftar Artikel')
        ->assertSee('Kejahatan Kini Tak Lagi Memiliki Kotak')
        ->assertSee('13 data ditemukan')
        ->assertSee('Draf')
        ->assertSee('Sampah')
        ->call('goToPage', 2)
        ->assertSet('page', 2)
        ->assertSee('Campaign funding: Lesson from Watergate')
        ->set('search', 'Watergate')
        ->assertSet('page', 1)
        ->assertSee('Campaign funding: Lesson from Watergate')
        ->assertDontSee('Kejahatan Kini Tak Lagi Memiliki Kotak');

    $article = Article::query()->where('title', 'Campaign funding: Lesson from Watergate')->firstOrFail();

    Livewire::test(ContentEntry::class)
        ->call('selectModule', 'articles')
        ->call('viewRecord', $article->id)
        ->assertSee('Detail metadata')
        ->assertSee('Campaign funding: Lesson from Watergate')
        ->assertSee('https://www.thejakartapost.com/news/2009/07/27/campaign-funding-lesson-watergate')
        ->call('closeDetails')
        ->assertSet('viewingId', null);
});

it('shows draft records in admin but not on the public archive and can publish from a list', function () {
    $article = Article::query()->firstOrFail()->replicate();
    $article->seed_key = hash('sha256', 'admin:'.Str::uuid());
    $article->title = 'Draf Kajian Transaksi Pencucian Uang';
    $article->is_published = false;
    $article->sort_order = 0;
    $article->save();

    Livewire::test(Articles::class)
        ->set('search', 'Draf Kajian Transaksi Pencucian Uang')
        ->assertDontSee('Draf Kajian Transaksi Pencucian Uang');

    Livewire::test(ContentEntry::class)
        ->call('selectModule', 'articles')
        ->call('setStatusFilter', 'draft')
        ->assertSet('statusFilter', 'draft')
        ->assertSee('Draf Kajian Transaksi Pencucian Uang')
        ->call('updatePublication', $article->id, true)
        ->assertDontSee('Draf Kajian Transaksi Pencucian Uang')
        ->call('setStatusFilter', 'published')
        ->assertSee('Draf Kajian Transaksi Pencucian Uang')
        ->call('updatePublication', $article->id, false)
        ->assertDontSee('Draf Kajian Transaksi Pencucian Uang');

    expect($article->fresh()->is_published)->toBeFalse();
});

it('edits an existing article without changing its source labels, month-only date or seed key', function () {
    $article = Article::query()->where('title', 'Pencucian Uang Profesional')->firstOrFail();
    $originalDate = $article->date_label;
    $originalLabel = $article->category_label;
    $originalAccess = $article->access_label;
    $originalSeedKey = $article->seed_key;

    expect($originalDate)->toBe('Agustus 2026');

    Livewire::test(ContentEntry::class)
        ->call('selectModule', 'articles')
        ->call('editRecord', $article->id)
        ->assertSet('editingId', $article->id)
        ->assertSet('entry.date', '')
        ->assertSee('Tanggal sebelumnya: Agustus 2026')
        ->set('entry.title', 'Analisis Baru Pencucian Uang Profesional')
        ->set('entry.excerpt', 'Kajian yang diperbarui tentang modus pencucian uang profesional dan kendali atas aset.')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSee('berhasil diperbarui');

    $fresh = $article->fresh();
    expect($fresh->title)->toBe('Analisis Baru Pencucian Uang Profesional')
        ->and($fresh->date_label)->toBe($originalDate)
        ->and($fresh->category_label)->toBe($originalLabel)
        ->and($fresh->access_label)->toBe($originalAccess)
        ->and($fresh->seed_key)->toBe($originalSeedKey);

    $this->assertDatabaseCount('articles', 13);
    Livewire::test(Articles::class)
        ->set('search', 'Analisis Baru Pencucian Uang Profesional')
        ->assertSee('Analisis Baru Pencucian Uang Profesional');
});

it('edits library metadata without losing original topic labels, institution or source access', function () {
    $library = LibraryResource::query()->where('title', 'YAYASAN, SOEKARNO DAN PENCUCIAN UANG')->firstOrFail();
    $originalLabels = $library->topic_labels;
    $originalAccess = $library->access_label;
    $originalType = $library->type_label;

    Livewire::test(ContentEntry::class)
        ->call('selectModule', 'library')
        ->call('editRecord', $library->id)
        ->assertSet('entry.topics', $library->topics)
        ->set('entry.summary', 'Tulisan arsip yang diperbarui mengenai yayasan, asal dana, dan risiko tindak pidana pencucian uang.')
        ->call('save')
        ->assertHasNoErrors();

    $fresh = $library->fresh();
    expect($fresh->topic_labels)->toBe($originalLabels)
        ->and($fresh->type_label)->toBe($originalType)
        ->and($fresh->access_label)->toBe($originalAccess)
        ->and($fresh->summary)->toContain('diperbarui');

    Livewire::test(Library::class)
        ->set('search', 'YAYASAN, SOEKARNO')
        ->assertSee('YAYASAN, SOEKARNO DAN PENCUCIAN UANG');
});

it('edits media without overwriting its original year, date, role or thumbnail', function () {
    $media = MediaAppearance::query()->firstOrFail();
    $oldDate = $media->date_label;
    $oldYear = $media->year;
    $oldUrl = $media->thumbnail_url;

    Livewire::test(ContentEntry::class)
        ->call('selectModule', 'media')
        ->call('editRecord', $media->id)
        ->assertSet('entry.date', '')
        ->set('entry.summary', 'Wawancara terkini mengenai penanganan kasus dan temuan transaksi judi online yang diperiksa PPATK.')
        ->call('save')
        ->assertHasNoErrors();

    $fresh = $media->fresh();
    expect($fresh->date_label)->toBe($oldDate)
        ->and($fresh->year)->toBe($oldYear)
        ->and($fresh->thumbnail_url)->toBe($oldUrl)
        ->and($fresh->summary)->toContain('terkini');

    Livewire::test(Media::class)
        ->set('search', 'Wawancara terkini')
        ->assertSee($fresh->title);
});

it('updates a media thumbnail and can remove only its managed upload', function () {
    Storage::fake('public');

    $media = MediaAppearance::query()->firstOrFail();
    $media->thumbnail_url = '/storage/media/thumbnails/previous.jpg';
    $media->save();
    Storage::disk('public')->put('media/thumbnails/previous.jpg', 'previous image');

    Livewire::test(ContentEntry::class)
        ->call('selectModule', 'media')
        ->call('editRecord', $media->id)
        ->set('thumbnail', UploadedFile::fake()->image('new-thumbnail.jpg', 480, 320))
        ->call('save')
        ->assertHasNoErrors();

    $newThumbnail = $media->fresh()->thumbnail_url;
    expect($newThumbnail)->toStartWith('/storage/media/thumbnails/')
        ->not->toBe('/storage/media/thumbnails/previous.jpg');

    Storage::disk('public')->assertMissing('media/thumbnails/previous.jpg');
    Storage::disk('public')->assertExists(Str::after($newThumbnail, '/storage/'));

    Livewire::test(ContentEntry::class)
        ->call('selectModule', 'media')
        ->call('editRecord', $media->id)
        ->set('removeThumbnail', true)
        ->call('save')
        ->assertHasNoErrors();

    expect($media->fresh()->thumbnail_url)->toBeNull();
    Storage::disk('public')->assertMissing(Str::after($newThumbnail, '/storage/'));
});

it('requires explicit delete confirmation and restores deleted seeded articles as drafts', function () {
    $article = Article::query()->firstOrFail();
    $id = $article->id;
    $key = $article->seed_key;

    Livewire::test(ContentEntry::class)
        ->call('selectModule', 'articles')
        ->call('requestDelete', $id)
        ->assertSet('pendingDeleteId', $id)
        ->assertSee('Ya, pindahkan ke Sampah')
        ->call('cancelDelete')
        ->assertSet('pendingDeleteId', null);

    expect(Article::query()->find($id))->not->toBeNull();

    Livewire::test(ContentEntry::class)
        ->call('selectModule', 'articles')
        ->call('requestDelete', $id)
        ->call('confirmDelete')
        ->assertSet('pendingDeleteId', null)
        ->assertSee('dipindahkan ke Sampah');

    expect(Article::query()->find($id))->toBeNull()
        ->and(Article::onlyTrashed()->findOrFail($id)->seed_key)->toBe($key);

    $this->seed(ContentSeeder::class); // Never resurrect a deliberately deleted seed.
    expect(Article::query()->find($id))->toBeNull();

    Livewire::test(ContentEntry::class)
        ->call('selectModule', 'articles')
        ->call('setStatusFilter', 'trash')
        ->assertSee($article->title)
        ->call('restoreRecord', $id)
        ->assertSet('viewingId', null)
        ->assertDontSee($article->title);

    expect(Article::query()->findOrFail($id)->is_published)->toBeFalse();

    Livewire::test(Articles::class)
        ->set('search', $article->title)
        ->assertDontSee($article->title);
});

it('soft deletes and restores library and media entries from their own module', function () {
    foreach ([
        'library' => LibraryResource::class,
        'media' => MediaAppearance::class,
    ] as $module => $model) {
        $record = $model::query()->firstOrFail();

        Livewire::test(ContentEntry::class)
            ->call('selectModule', $module)
            ->call('requestDelete', $record->id)
            ->call('confirmDelete')
            ->assertHasNoErrors();

        expect($model::query()->find($record->id))->toBeNull();

        Livewire::test(ContentEntry::class)
            ->call('selectModule', $module)
            ->call('setStatusFilter', 'trash')
            ->assertSee($record->title)
            ->call('restoreRecord', $record->id)
            ->assertHasNoErrors();

        expect($record->fresh()->is_published)->toBeFalse();
    }
});

it('clears edit state when changing module and rejects invalid or removed record ids', function () {
    $articleId = Article::query()->firstOrFail()->id;

    Livewire::test(ContentEntry::class)
        ->call('selectModule', 'articles')
        ->call('editRecord', $articleId)
        ->assertSet('editingId', $articleId)
        ->call('selectModule', 'media')
        ->assertSet('editingId', null)
        ->assertSet('entry.title', '');

    Livewire::test(ContentEntry::class)
        ->call('selectModule', 'media')
        ->call('editRecord', 999999)
        ->assertNotFound();

    Livewire::test(ContentEntry::class)
        ->call('selectModule', 'library')
        ->call('updatePublication', 999999, true)
        ->assertNotFound();
});
