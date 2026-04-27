<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AdminPackageTypeController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/PackageTypes/Index', [
            'types' => PackageType::orderBy('sort_order')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/PackageTypes/Form', ['type' => null]);
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'name'          => 'required|string|max:100',
            'description'   => 'nullable|string|max:300',
            'icon_emoji'    => 'nullable|string|max:10',
            'hero_image_url'=> 'nullable|url|max:500',
            'gradient'      => 'nullable|string|max:100',
            'badge_class'   => 'nullable|string|max:100',
            'sort_order'    => 'nullable|integer|min:0|max:255',
            'is_active'     => 'boolean',
        ]);

        $data['slug']     = Str::slug($data['name']);
        $data['type_key'] = Str::snake($data['name']);
        $data['is_active']  = $req->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 99;

        PackageType::create($data);

        return redirect('/admin/package-types')->with('success', 'Package type created!');
    }

    public function edit(PackageType $packageType)
    {
        return Inertia::render('Admin/PackageTypes/Form', ['type' => $packageType]);
    }

    public function update(Request $req, PackageType $packageType)
    {
        $data = $req->validate([
            'name'          => 'required|string|max:100',
            'description'   => 'nullable|string|max:300',
            'icon_emoji'    => 'nullable|string|max:10',
            'hero_image_url'=> 'nullable|url|max:500',
            'gradient'      => 'nullable|string|max:100',
            'badge_class'   => 'nullable|string|max:100',
            'sort_order'    => 'nullable|integer|min:0|max:255',
            'is_active'     => 'boolean',
        ]);
        $data['is_active']  = $req->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 99;

        $packageType->update($data);
        return redirect('/admin/package-types')->with('success', 'Package type updated!');
    }

    public function destroy(PackageType $packageType)
    {
        $packageType->delete();
        return back()->with('success', 'Package type deleted.');
    }
}
