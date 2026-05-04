<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminCountryController extends Controller
{
    public function index()
    {
        $countries = Country::withCount('packages')->latest()->paginate(15);
        return Inertia::render('Admin/Countries/Index', [
            'countries' => $countries
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Countries/Form', [
            'country' => null
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('countries', 'public');
            $validated['cover_image'] = '/storage/' . $path;
        }

        Country::create($validated);
        \Illuminate\Support\Facades\Cache::forget('nav_countries');

        return redirect()->route('admin.countries.index')->with('success', 'Country added successfully.');
    }

    public function edit(Country $country)
    {
        return Inertia::render('Admin/Countries/Form', [
            'country' => $country
        ]);
    }

    public function update(Request $request, Country $country)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'cover_image' => 'nullable|image|max:5120',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('cover_image')) {
            if ($country->cover_image && str_starts_with($country->cover_image, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $country->cover_image));
            }
            $path = $request->file('cover_image')->store('countries', 'public');
            $validated['cover_image'] = '/storage/' . $path;
        } else {
            unset($validated['cover_image']);
        }

        $country->update($validated);
        \Illuminate\Support\Facades\Cache::forget('nav_countries');

        return redirect()->route('admin.countries.index')->with('success', 'Country updated successfully.');
    }

    public function destroy(Country $country)
    {
        if ($country->packages()->count() > 0) {
            return back()->with('error', 'Cannot delete country because it has assigned packages. Please reassign or delete the packages first.');
        }

        if ($country->cover_image && str_starts_with($country->cover_image, '/storage/')) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $country->cover_image));
        }

        $country->delete();
        \Illuminate\Support\Facades\Cache::forget('nav_countries');

        return redirect()->route('admin.countries.index')->with('success', 'Country deleted successfully.');
    }
}
