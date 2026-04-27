<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use Inertia\Inertia;

class GalleryController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::where('is_active', true)
            ->withCount('images')
            ->with(['images' => fn($q) => $q->latest()->limit(1)])
            ->orderBy('title')
            ->get();

        return Inertia::render('Public/Gallery/Index', [
            'albums' => $albums
        ]);
    }

    public function show($slug)
    {
        $album = GalleryAlbum::where('slug', $slug)
            ->where('is_active', true)
            ->with('images')
            ->firstOrFail();

        return Inertia::render('Public/Gallery/Show', [
            'album' => $album
        ]);
    }
}