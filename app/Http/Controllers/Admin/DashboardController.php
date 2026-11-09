<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\LibraryResource;
use App\Models\MediaAppearance;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.dashboard', [
            'admin' => $request->user(),
            'moduleCounts' => [
                'articles' => Article::query()->where('is_published', true)->count(),
                'library' => LibraryResource::query()->where('is_published', true)->count(),
                'media' => MediaAppearance::query()->where('is_published', true)->count(),
            ],
        ]);
    }
}
