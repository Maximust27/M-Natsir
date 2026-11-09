<?php

use App\Models\Article;
use App\Models\LibraryResource;
use App\Models\MediaAppearance;
use App\Models\User;
use App\Support\Content\ArticleCatalog;
use Database\Seeders\ContentSeeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use App\Livewire\Library;
use App\Livewire\Media;

it('creates separate schemas for each public card type and imports the original catalogues', function () {
    $this->assertDatabaseCount('articles', 13);
    $this->assertDatabaseCount('library_resources', 22);
    $this->assertDatabaseCount('media_appearances', 16);

    foreach ([
        'articles' => ['category', 'category_label', 'source', 'date_label', 'excerpt', 'url', 'access_label', 'is_primary_source', 'source_note'],
        'library_resources' => ['collection', 'document_type', 'institution', 'year_label', 'topics', 'topic_labels', 'author_label', 'role_label', 'summary'],
        'media_appearances' => ['category', 'badge_variant', 'date_label', 'year', 'outlet', 'role', 'topics', 'thumbnail_url', 'source_note'],
    ] as $table => $columns) {
        foreach ($columns as $column) {
            expect(Schema::hasColumn($table, $column))->toBeTrue();
        }

        expect(Schema::hasColumn($table, 'sort_order'))->toBeTrue();
        expect(Schema::hasColumn($table, 'is_published'))->toBeTrue();
        expect(Schema::hasColumn($table, 'seed_key'))->toBeTrue();
    }
});

it('keeps the original article order and attribution in the database', function () {
    $articles = ArticleCatalog::all();

    expect($articles)->toHaveCount(13)
        ->and($articles[0]['title'])->toBe('Kejahatan Kini Tak Lagi Memiliki Kotak')
        ->and($articles[0]['source'])->toBe('Majalah Editor · Insight')
        ->and($articles[0]['isPrimarySource'])->toBeTrue()
        ->and($articles[0]['url'])->toBe('https://majalaheditor.com/insight/kejahatan-kini-tak-lagi-memiliki-kotak/');

    expect(Article::query()->where('is_primary_source', false)->count())->toBe(2);
});

it('renders article edits from SQL across home and archive instead of reading PHP seed snapshots', function () {
    $article = Article::query()->orderBy('sort_order')->firstOrFail();
    $article->title = 'Judul Terbaru dari MySQL';
    $article->save();

    $this->get(route('home'))->assertOk()->assertSee('Judul Terbaru dari MySQL');
    $this->get(route('articles'))->assertOk()->assertSee('Judul Terbaru dari MySQL');

    expect(ArticleCatalog::featured(1)[0]['title'])->toBe('Judul Terbaru dari MySQL');
});

it('uses SQL for library resource changes without breaking topic filtering', function () {
    $resource = LibraryResource::query()->orderBy('sort_order')->firstOrFail();
    $resource->title = 'Dokumen Baru dari Database';
    $resource->save();

    Livewire::test(Library::class)
        ->set('collection', 'natsir')
        ->assertSee('Dokumen Baru dari Database');

    expect(LibraryResource::query()->where('collection', 'natsir')->count())->toBeGreaterThan(0);
    expect(LibraryResource::query()->where('year_label', 'Arsip')->exists())->toBeTrue();
});

it('uses SQL for media appearance changes and preserves the featured view', function () {
    $media = MediaAppearance::query()->orderBy('sort_order')->firstOrFail();
    $media->title = 'Penampilan Baru dari Database';
    $media->save();

    $this->get(route('media'))->assertOk()->assertSee('Penampilan Baru dari Database');

    Livewire::test(Media::class)
        ->set('search', 'Penampilan Baru dari Database')
        ->assertSee('Penampilan Baru dari Database');
});

it('hides unpublished records from their public page without deleting their sources', function () {
    $article = Article::query()->orderBy('sort_order')->firstOrFail();
    $article->is_published = false;
    $article->save();

    $library = LibraryResource::query()->orderBy('sort_order')->firstOrFail();
    $library->is_published = false;
    $library->save();

    $media = MediaAppearance::query()->orderBy('sort_order')->firstOrFail();
    $media->is_published = false;
    $media->save();

    expect(ArticleCatalog::all())->toHaveCount(12)
        ->and(ArticleCatalog::featured(3))->toHaveCount(3);

    $this->get(route('articles'))->assertDontSee($article->title);
    $this->get(route('library'))->assertDontSee($library->title);
    $this->get(route('media'))->assertDontSee($media->title);
});

it('seeds data idempotently without overwriting edited content or existing admin users', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $record = Article::query()->orderBy('sort_order')->firstOrFail();
    $record->title = 'Judul hasil penyuntingan';
    $record->save();

    $this->seed(ContentSeeder::class);

    $this->assertDatabaseCount('articles', 13);
    $this->assertDatabaseCount('library_resources', 22);
    $this->assertDatabaseCount('media_appearances', 16);

    expect($record->fresh()->title)->toBe('Judul hasil penyuntingan')
        ->and($admin->fresh()->is_admin)->toBeTrue()
        ->and(User::query()->count())->toBe(1);
});

it('uses the layout for the sole logout action and shows live content counts on the admin dashboard', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $hiddenArticle = Article::query()->orderBy('sort_order')->firstOrFail();
    $hiddenArticle->is_published = false;
    $hiddenArticle->save();

    $html = $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('12 data tersimpan')
        ->assertSee('22 data tersimpan')
        ->assertSee('16 data tersimpan')
        ->assertSee('Kembali ke website')
        ->getContent();

    expect(substr_count($html, 'action="'.route('admin.logout').'"'))->toBe(1);
    expect(File::get(resource_path('views/admin/dashboard.blade.php')))
        ->not->toContain("route('admin.logout')");
});

it('shows empty states safely if content has not been seeded yet', function () {
    Article::query()->delete();
    LibraryResource::query()->delete();
    MediaAppearance::query()->delete();

    $this->get(route('home'))->assertOk();
    $this->get(route('articles'))->assertOk();
    $this->get(route('library'))->assertOk();
    $this->get(route('media'))->assertOk();
});
