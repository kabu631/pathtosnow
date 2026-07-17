<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminTestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::latest()->paginate(15);
        return Inertia::render('Admin/Testimonials/Index', [
            'testimonials' => $testimonials
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Testimonials/Form', [
            'testimonial' => null
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'quote'       => 'required|string',
            'author'      => 'required|string|max:255',
            'location'    => 'nullable|string|max:255',
            'avatar_file' => 'nullable|image|max:2048',
            'rating'      => 'required|integer|min:1|max:5',
            'is_active'   => 'boolean',
        ]);

        if ($request->hasFile('avatar_file')) {
            $validated['avatar'] = ImageService::store(
                $request->file('avatar_file'),
                'testimonials'
            );
        }

        unset($validated['avatar_file']);

        Testimonial::create($validated);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created successfully.');
    }

    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return Inertia::render('Admin/Testimonials/Form', [
            'testimonial' => $testimonial
        ]);
    }

    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $validated = $request->validate([
            'quote'       => 'required|string',
            'author'      => 'required|string|max:255',
            'location'    => 'nullable|string|max:255',
            'avatar_file' => 'nullable|image|max:2048',
            'rating'      => 'required|integer|min:1|max:5',
            'is_active'   => 'boolean',
        ]);

        if ($request->hasFile('avatar_file')) {
            ImageService::delete($testimonial->avatar);
            $validated['avatar'] = ImageService::store(
                $request->file('avatar_file'),
                'testimonials'
            );
        }

        unset($validated['avatar_file']);

        $testimonial->update($validated);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated successfully.');
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        ImageService::delete($testimonial->avatar);
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted.');
    }

    public function toggle($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->update(['is_active' => !$testimonial->is_active]);
        return back()->with('success', $testimonial->is_active ? 'Testimonial activated.' : 'Testimonial deactivated.');
    }
}
