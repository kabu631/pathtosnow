<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\{Product, Category};

class AdminProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Products/Index', [
            'products'   => Product::with('category:id,name')->latest()->paginate(15),
            'categories' => Category::orderBy('sort_order')->get(['id', 'name']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Products/Form', [
            'product'    => null,
            'categories' => Category::orderBy('sort_order')->get(['id', 'name']),
        ]);
    }

    public function store(Request $req)
    {
        $data = $this->validateProduct($req);
        $data['images'] = $this->handleImages($req, $data['images'] ?? []);
        Product::create($data);
        return redirect()->route('admin.products.index')->with('success', 'Product created!');
    }

    public function edit(Product $product)
    {
        return Inertia::render('Admin/Products/Form', [
            'product'    => $product,
            'categories' => Category::orderBy('sort_order')->get(['id', 'name']),
        ]);
    }

    public function update(Request $req, Product $product)
    {
        $data = $this->validateProduct($req, $product);
        $data['images'] = $this->handleImages($req, $data['images'] ?? []);
        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Product updated!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Deleted.');
    }

    private function validateProduct(Request $req, ?Product $product = null): array
    {
        $validated = $req->validate([
            'category_id'      => 'nullable|exists:categories,id',
            'name'             => 'required|string|max:255',
            'slug'             => 'nullable|string',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'compare_price'    => 'nullable|numeric',
            'images'           => 'nullable|array',
            'new_images'       => 'nullable|array',
            'new_images.*'     => 'image|max:5120',
            'stock'            => 'integer|min:0',
            'sku'              => 'nullable|string',
            'weight_grams'     => 'nullable|integer',
            'tags'             => 'nullable|array',
            'specs'            => 'nullable|array',
            'featured'         => 'boolean',
            'active'           => 'boolean',
            'meta_title'       => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
        ]);
        
        $slug = Str::slug($req->name);
        $originalSlug = $slug;
        $count = 1;
        while(Product::where('slug', $slug)->where('id', '!=', $product?->id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $validated + ['slug' => $slug];
    }

    private function handleImages(Request $req, array $existingImages): array
    {
        $images = $existingImages;
        if ($req->hasFile('new_images')) {
            foreach ($req->file('new_images') as $file) {
                $path = $file->store('products', 'public');
                $images[] = '/storage/' . $path;
            }
        }
        return $images;
    }
}
