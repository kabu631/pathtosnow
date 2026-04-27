<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryImageController extends Controller
{
    public function store(Request $request, GalleryAlbum $gallery)
    {
        $request->validate([
            'images.*' => 'required|image|max:5120', // 5MB max per image
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('gallery_images/' . $gallery->id, 'public');
                $gallery->images()->create([
                    'image_path' => '/storage/' . $path,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Images uploaded successfully.');
    }

    public function destroy(GalleryImage $image)
    {
        if ($image->image_path && str_starts_with($image->image_path, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $image->image_path));
        }
        $image->delete();

        return redirect()->back()->with('success', 'Image deleted.');
    }
}
