<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\Post;

class BlogController extends Controller
{
    public function dynamicCategory(string $slug)
    {
        $postType = \App\Models\PostType::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $posts = Post::published()
            ->where('post_type', $postType->type_key)
            ->when(request('q'), fn($q, $s) => $q->where('title', 'like', "%$s%"))
            ->latest('published_at')->paginate(9)->withQueryString();

        return Inertia::render('Public/Blog/Category', [
            'posts'   => $posts,
            'filters' => ['q' => request('q')],
            'type'    => $postType->type_key,
            'postType'=> $postType,
        ]);
    }

    private function postsByType(string $type, string $view)
    {
        $postType = \App\Models\PostType::where('type_key', $type)->first();

        $posts = Post::published()
            ->where('post_type', $type)
            ->when(request('q'), fn($q, $s) => $q->where('title', 'like', "%$s%"))
            ->latest('published_at')->paginate(9)->withQueryString();

        return Inertia::render($view, [
            'posts'   => $posts,
            'filters' => ['q' => request('q')],
            'type'    => $type,
            'postType'=> $postType,
        ]);
    }

    public function guideIndex()
    {
        return Inertia::render('Public/Blog/Index', [
            'posts' => Post::published()->with('author:id,name')
                ->where('post_type', '!=', 'blog')
                ->when(request('q'), fn($q, $s) => $q->where('title', 'like', "%$s%"))
                ->latest('published_at')->paginate(12)->withQueryString(),
            'filters' => ['q' => request('q')],
            'types'   => Post::TYPE_LABELS,
        ]);
    }

    public function food()      { return $this->postsByType('food',        'Public/Blog/Category'); }
    public function culture()   { return $this->postsByType('culture',     'Public/Blog/Category'); }
    public function festivals() { return $this->postsByType('festival',    'Public/Blog/Category'); }
    public function cityTours() { return $this->postsByType('city_tour',   'Public/Blog/Category'); }

    public function index()
    {
        return Inertia::render('Public/Blog/Index', [
            'posts' => Post::published()->with('author:id,name')
                ->when(request('type'), fn($q, $t) => $q->where('post_type', $t))
                ->when(request('q'),    fn($q, $s) => $q->where('title', 'like', "%$s%"))
                ->latest('published_at')->paginate(12)->withQueryString(),
            'filters' => ['type' => request('type'), 'q' => request('q')],
            'types'   => Post::TYPE_LABELS,
        ]);
    }

    public function show(string $slug)
    {
        $post = Post::published()->with(['author:id,name', 'relatedPackage:id,name,slug,type'])
            ->where('slug', $slug)->firstOrFail();
        $post->incrementViews();
        return Inertia::render('Public/Blog/Show', [
            'post'    => $post,
            'related' => $post->related(3),
        ]);
    }
}
