<?php

use App\Http\Controllers\SeoController;
use App\Livewire\About;
use App\Livewire\Articles;
use App\Livewire\Contact;
use App\Livewire\Home;
use App\Livewire\Library;
use App\Livewire\Media;
use App\Livewire\Topic;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

Route::livewire('/', Home::class)->name('home');
Route::livewire('/tentang', About::class)->name('about');
Route::livewire('/articles', Articles::class)->name('articles');
Route::livewire('/topik/{slug}', Topic::class)->name('topics.show');
Route::livewire('/library', Library::class)->name('library');
Route::livewire('/media', Media::class)->name('media');

Route::get('/contact/cv', function () {
    $path = public_path('files/m-natsir-kongah-cv.pdf');

    abort_unless(is_file($path), 404);

    return response()->streamDownload(
        function () use ($path): void {
            $stream = fopen($path, 'rb');

            if ($stream === false) {
                return;
            }

            fpassthru($stream);
            fclose($stream);
        },
        'M-Natsir-Kongah-CV.pdf',
        [
            'Content-Type' => 'application/pdf',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ],
    );
})->name('contact.cv.download');

Route::livewire('/contact', Contact::class)->name('contact');
