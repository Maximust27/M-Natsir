<?php

use App\Livewire\About;
use App\Livewire\Articles;
use App\Livewire\Contact;
use App\Livewire\Home;
use App\Livewire\Library;
use App\Livewire\Media;
use Illuminate\Support\Facades\Route;

Route::livewire('/', Home::class)->name('home');
Route::livewire('/tentang', About::class)->name('about');
Route::livewire('/articles', Articles::class)->name('articles');
Route::livewire('/library', Library::class)->name('library');
Route::livewire('/media', Media::class)->name('media');

Route::get('/contact/cv', function () {
    $path = public_path('files/m-natsir-kongah-cv.pdf');

    abort_unless(is_file($path), 404);

    return response()->download(
        $path,
        'M-Natsir-Kongah-CV.pdf',
        ['Content-Type' => 'application/pdf'],
    );
})->name('contact.cv.download');

Route::livewire('/contact', Contact::class)->name('contact');
