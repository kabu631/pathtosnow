<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class AdminLocationController extends Controller
{
    public function index()
    {
        $locations = Location::withCount('packages')->latest()->paginate(20);
        return Inertia::render('Admin/Locations/Index', [
            'locations' => $locations
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Locations/Form', [
            'location' => null
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'is_active'        => 'boolean',
            'cover_image_file' => 'nullable|image|max:5120',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('cover_image_file')) {
            $validated['cover_image'] = ImageService::store(
                $request->file('cover_image_file'),
                'location_covers'
            );
        }

        unset($validated['cover_image_file']);

        Location::create($validated);

        return redirect()->route('admin.locations.index')->with('success', 'Location created successfully.');
    }

    public function show($id)
    {
        $location = Location::with('images')->findOrFail($id);
        return Inertia::render('Admin/Locations/Show', [
            'location' => $location
        ]);
    }

    public function edit($id)
    {
        $location = Location::findOrFail($id);
        return Inertia::render('Admin/Locations/Form', [
            'location' => $location
        ]);
    }

    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);

        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'is_active'        => 'boolean',
            'cover_image_file' => 'nullable|image|max:5120',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('cover_image_file')) {
            ImageService::delete($location->cover_image);
            $validated['cover_image'] = ImageService::store(
                $request->file('cover_image_file'),
                'location_covers'
            );
        }

        unset($validated['cover_image_file']);

        $location->update($validated);

        return redirect()->route('admin.locations.index')->with('success', 'Location updated successfully.');
    }

    public function destroy($id)
    {
        $location = Location::with('images')->findOrFail($id);

        ImageService::delete($location->cover_image);

        foreach ($location->images as $image) {
            ImageService::delete($image->image_path);
        }

        $location->delete();

        return redirect()->route('admin.locations.index')->with('success', 'Location and all its images deleted.');
    }
}
