<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AdminPostTypeController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/PostTypes/Index', [
            'types' => PostType::orderBy('sort_order')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/PostTypes/Form', ['type' => null]);
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'name'           => 'required|string|max:100',
            'icon_emoji'     => 'nullable|string|max:10',
            'hero_image_url' => 'nullable|url|max:2048',
            'color'          => 'nullable|string|max:50',
            'sort_order'     => 'nullable|integer|min:0|max:255',
            'is_active'      => 'boolean',
        ]);

        $data['slug']      = Str::slug($data['name']);
        $data['type_key']  = Str::snake($data['name']);
        $data['is_active'] = $req->boolean('is_active', true);
        $data['sort_order']= $data['sort_order'] ?? 99;

        PostType::create($data);
        return redirect('/admin/post-types')->with('success', 'Post type created!');
    }

    public function edit(PostType $postType)
    {
        return Inertia::render('Admin/PostTypes/Form', ['type' => $postType]);
    }

    public function update(Request $req, PostType $postType)
    {
        $data = $req->validate([
            'name'           => 'required|string|max:100',
            'icon_emoji'     => 'nullable|string|max:10',
            'hero_image_url' => 'nullable|url|max:2048',
            'color'          => 'nullable|string|max:50',
            'sort_order'     => 'nullable|integer|min:0|max:255',
            'is_active'      => 'boolean',
        ]);
        $data['is_active'] = $req->boolean('is_active', true);
        $data['sort_order']= $data['sort_order'] ?? 99;

        $postType->update($data);
        return redirect('/admin/post-types')->with('success', 'Post type updated!');
    }

    public function destroy(PostType $postType)
    {
        $postType->delete();
        return back()->with('success', 'Post type deleted.');
    }
}
