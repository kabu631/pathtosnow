<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\{Post, Package, GalleryAlbum, GalleryImage, PostType};

class AdminPostController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Posts/Index', [
            'posts' => Post::with('author:id,name')
                ->when(request('type'), fn($q, $t) => $q->where('post_type', $t))
                ->when(request('q'),    fn($q, $s) => $q->where('title', 'like', "%$s%"))
                ->latest()->paginate(15)->withQueryString(),
            'types' => PostType::orderBy('sort_order')->pluck('name', 'type_key'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Posts/Form', [
            'post'     => null,
            'types'    => PostType::active()->orderBy('sort_order')->pluck('name', 'type_key'),
            'packages' => Package::active()->orderBy('name')->get(['id', 'name', 'type']),
            'albums'   => GalleryAlbum::orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function store(Request $req)
    {
        $data = $this->validatePost($req);
        $data['user_id']      = auth()->id();
        $data['published_at'] = $data['published'] ? now() : null;
        Post::create($data);
        return redirect()->route('admin.posts.index')->with('success', 'Post created!');
    }

    public function edit(Post $post)
    {
        return Inertia::render('Admin/Posts/Form', [
            'post'     => $post,
            'types'    => PostType::active()->orderBy('sort_order')->pluck('name', 'type_key'),
            'packages' => Package::active()->orderBy('name')->get(['id', 'name', 'type']),
            'albums'   => GalleryAlbum::orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function update(Request $req, Post $post)
    {
        $data = $this->validatePost($req);
        if ($data['published'] && !$post->published) $data['published_at'] = now();
        $post->update($data);
        return redirect()->route('admin.posts.index')->with('success', 'Post updated!');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return back()->with('success', 'Post deleted.');
    }

    public function togglePublish(Post $post)
    {
        $post->update(['published' => !$post->published, 'published_at' => !$post->published ? now() : null]);
        return back()->with('success', $post->published ? 'Published.' : 'Unpublished.');
    }

    private function validatePost(Request $req): array
    {
        return $req->validate([
            'title'              => 'required|string|max:255',
            'slug'               => 'nullable|string',
            'excerpt'            => 'nullable|string|max:500',
            'content'            => 'required|string',
            'cover_image'        => 'nullable|image|max:5120',
            'photo_album_id'     => 'nullable|string',
            'new_album_title'    => 'nullable|string|max:255',
            'post_type'          => 'required|in:blog,food,culture,festival,city_tour,travel_guide',
            'category'           => 'nullable|string|max:100',
            'tags'               => 'nullable|array',
            'tags.*'             => 'string',
            'published'          => 'boolean',
            'read_time'          => 'integer|min:1',
            'meta_title'         => 'nullable|string|max:60',
            'meta_description'   => 'nullable|string|max:160',
            'related_package_id' => 'nullable|exists:packages,id',
        ]) + ['slug' => Str::slug($req->title)];
    }
}
