<?php

use App\Livewire\About;
use App\Livewire\Articles;
use App\Livewire\Home;
use App\Livewire\Library;
use Illuminate\Support\Facades\Route;

Route::livewire('/', Home::class)->name('home');
Route::livewire('/tentang', About::class)->name('about');
Route::livewire('/articles', Articles::class)->name('articles');
Route::livewire('/library', Library::class)->name('library');
