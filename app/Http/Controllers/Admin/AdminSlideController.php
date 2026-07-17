<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Slide;
use App\Services\ImageService;

class AdminSlideController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Slides/Index', [
            'slides' => Slide::orderBy('sort_order')->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Slides/Form', [
            'slide' => null
        ]);
    }

    public function store(Request $req)
    {
        $data = $this->validateSlide($req);
        if ($req->hasFile('new_image')) {
            $data['image'] = $this->handleImage($req);
        }
        
        if (!isset($data['sort_order'])) {
            $data['sort_order'] = Slide::max('sort_order') + 1;
        }

        Slide::create($data);
        return redirect()->route('admin.slides.index')->with('success', 'Slide created.');
    }

    public function edit(Slide $slide)
    {
        return Inertia::render('Admin/Slides/Form', [
            'slide' => $slide
        ]);
    }

    public function update(Request $req, Slide $slide)
    {
        $data = $this->validateSlide($req);
        if ($req->hasFile('new_image')) {
            $data['image'] = $this->handleImage($req);
        }
        $slide->update($data);
        return redirect()->route('admin.slides.index')->with('success', 'Slide updated.');
    }

    public function destroy(Slide $slide)
    {
        $slide->delete();
        return back()->with('success', 'Slide deleted.');
    }

    public function reorder(Request $req)
    {
        $req->validate([
            'slides' => 'required|array',
            'slides.*.id' => 'required|exists:slides,id',
            'slides.*.sort_order' => 'required|integer'
        ]);

        foreach ($req->slides as $item) {
            Slide::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }
        
        return back()->with('success', 'Order updated.');
    }

    private function validateSlide(Request $req)
    {
        return $req->validate([
            'tag' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'desc' => 'nullable|string',
            'btn_text' => 'nullable|string|max:100',
            'btn_link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
            'new_image' => 'nullable|image|max:5120',
        ]);
    }

    private function handleImage(Request $req)
    {
        return ImageService::store($req->file('new_image'), 'slides');
    }
}
