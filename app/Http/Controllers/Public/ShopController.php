<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\{Product, Category};

class ShopController extends Controller
{
    public function index()
    {
        $sort    = request('sort', 'newest');
        $sortMap = [
            'newest'     => ['created_at', 'desc'],
            'price_asc'  => ['price', 'asc'],
            'price_desc' => ['price', 'desc'],
            'name_asc'   => ['name', 'asc'],
        ];
        [$col, $dir] = $sortMap[$sort] ?? ['created_at', 'desc'];

        return Inertia::render('Public/Shop/Index', [
            'products'   => Product::active()->with('category:id,name,slug')
                ->when(request('category'), fn($q, $c) => $q->whereHas('category', fn($q2) => $q2->where('slug', $c)))
                ->when(request('q'),        fn($q, $s) => $q->where('name', 'like', "%$s%"))
                ->when(request('in_stock'), fn($q)     => $q->where('stock', '>', 0))
                ->when(request('max_price'),fn($q, $p) => $q->where('price', '<=', $p))
                ->orderBy($col, $dir)->paginate(12)->withQueryString(),
            'categories' => Category::orderBy('sort_order')->get(['id', 'name', 'slug']),
            'filters'    => request()->only(['category', 'q', 'sort', 'in_stock', 'max_price']),
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::active()->with('category:id,name,slug')->where('slug', $slug)->firstOrFail();
        return Inertia::render('Public/Shop/Show', [
            'product' => $product,
            'related' => Product::active()
                ->where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->limit(4)->get(['id','name','slug','price','images','category_id']),
        ]);
    }
}
