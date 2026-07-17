<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Location;
use App\Models\Product;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q', '');
        
        $packages = [];
        $locations = [];
        $products = [];
        $posts = [];
        
        if (trim($q) !== '') {
            $packages = Package::active()
                ->where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                        ->orWhere('location', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhere('short_description', 'like', "%{$q}%");
                })
                ->orderBy('featured', 'desc')
                ->latest()
                ->get();

            $locations = Location::where('is_active', true)
                ->where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
                })
                ->withCount(['packages as packages_count'])
                ->orderBy('name')
                ->get();

            $products = Product::active()
                ->where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhere('tags', 'like', "%{$q}%");
                })
                ->orderBy('featured', 'desc')
                ->latest()
                ->get();

            $posts = Post::published()
                ->where(function ($query) use ($q) {
                    $query->where('title', 'like', "%{$q}%")
                        ->orWhere('excerpt', 'like', "%{$q}%")
                        ->orWhere('content', 'like', "%{$q}%");
                })
                ->latest('published_at')
                ->get();
        }

        return Inertia::render('Public/Search/Index', [
            'results' => [
                'packages' => $packages,
                'locations' => $locations,
                'products' => $products,
                'posts' => $posts,
            ],
            'filters' => [
                'q' => $q,
            ]
        ]);
    }
}
