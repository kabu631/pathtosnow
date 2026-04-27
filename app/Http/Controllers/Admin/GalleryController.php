<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::withCount('images')->latest()->paginate(20);
        return Inertia::render('Admin/Gallery/Index', [
            'albums' => $albums
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Gallery/Form', [
            'album' => new GalleryAlbum()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'cover_image' => 'nullable|image|max:5120', // 5MB
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('gallery_covers', 'public');
            $validated['cover_image'] = '/storage/' . $path;
        }

        GalleryAlbum::create($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Album created successfully.');
    }

    public function show(GalleryAlbum $gallery)
    {
        $gallery->load('images');
        return Inertia::render('Admin/Gallery/Show', [
            'album' => $gallery
        ]);
    }

    public function edit(GalleryAlbum $gallery)
    {
        return Inertia::render('Admin/Gallery/Form', [
            'album' => $gallery
        ]);
    }

    public function update(Request $request, GalleryAlbum $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('cover_image')) {
            if ($gallery->cover_image && str_starts_with($gallery->cover_image, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $gallery->cover_image));
            }
            $path = $request->file('cover_image')->store('gallery_covers', 'public');
            $validated['cover_image'] = '/storage/' . $path;
        }

        $gallery->update($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Album updated successfully.');
    }

    public function destroy(GalleryAlbum $gallery)
    {
        if ($gallery->cover_image && str_starts_with($gallery->cover_image, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $gallery->cover_image));
        }

        foreach ($gallery->images as $image) {
            if ($image->image_path && str_starts_with($image->image_path, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $image->image_path));
            }
        }

        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Album and all its images deleted.');
    }
}
