<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\LocationImage;
use App\Services\ImageService;
use Illuminate\Http\Request;

class LocationImageController extends Controller
{
    public function store(Request $request, $locationId)
    {
        $location = Location::findOrFail($locationId);

        $request->validate([
            'images.*' => 'required|image|max:5120',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = ImageService::store($file, "location_images/{$location->id}");
                // Eloquent sets location_id automatically via the hasMany relationship.
                $location->images()->create([
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Images uploaded successfully.');
    }

    public function destroy($id)
    {
        $image = LocationImage::findOrFail($id);
        ImageService::delete($image->image_path);
        $image->delete();

        return redirect()->back()->with('success', 'Image deleted.');
    }
}
