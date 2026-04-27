<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Package;
use App\Models\PackageType;

class AdminPackageController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Packages/Index', [
            'packages' => Package::when(request('type'), fn($q, $t) => $q->ofType($t))
                ->when(request('q'), fn($q, $s) => $q->where('name', 'like', "%$s%"))
                ->withCount('bookings')
                ->orderBy('type')->latest()->paginate(15)->withQueryString(),
            'types' => PackageType::orderBy('sort_order')->pluck('name', 'type_key'),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Packages/Form', [
            'package' => null,
            'types'   => PackageType::active()->orderBy('sort_order')->pluck('name', 'type_key'),
        ]);
    }

    public function store(Request $req)
    {
        $data = $this->validatePkg($req);
        Package::create($data);
        return redirect()->route('admin.packages.index')->with('success', 'Package created!');
    }

    public function edit(Package $package)
    {
        return Inertia::render('Admin/Packages/Form', [
            'package' => $package->load('itineraryDays'),
            'types'   => PackageType::active()->orderBy('sort_order')->pluck('name', 'type_key'),
        ]);
    }

    public function update(Request $req, Package $package)
    {
        $package->update($this->validatePkg($req, $package));
        return redirect()->route('admin.packages.index')->with('success', 'Package updated!');
    }

    public function destroy(Package $package)
    {
        $package->delete();
        return back()->with('success', 'Package deleted.');
    }

    public function toggle(Package $package)
    {
        $package->update(['active' => !$package->active]);
        return back()->with('success', $package->active ? 'Package activated.' : 'Package deactivated.');
    }

    private function validatePkg(Request $req, ?Package $package = null): array
    {
        $data = $req->validate([
            'type'              => 'required|in:adventure,valley_visit,trekking,national_park,wildlife_reserve,lake',
            'name'              => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255',
            'location'          => 'required|string|max:255',
            'region'            => 'nullable|string|max:255',
            'short_description' => 'required|string|max:500',
            'description'       => 'required|string',
            'cover_image'       => 'nullable|string',
            'gallery'           => 'nullable|array',
            'price_per_person'  => 'required|numeric|min:0',
            'price_group'       => 'nullable|numeric|min:0',
            'duration_days'     => 'required|integer|min:1',
            'duration_nights'   => 'nullable|integer',
            'min_group_size'    => 'integer|min:1',
            'max_group_size'    => 'integer|min:1',
            'difficulty'        => 'nullable|in:easy,moderate,challenging,strenuous',
            'max_altitude_m'    => 'nullable|integer',
            'best_season'       => 'nullable|string|max:100',
            'start_point'       => 'nullable|string|max:255',
            'end_point'         => 'nullable|string|max:255',
            'highlights'        => 'nullable|array',
            'included'          => 'nullable|array',
            'excluded'          => 'nullable|array',
            'requirements'      => 'nullable|array',
            'faqs'              => 'nullable|array',
            'featured'          => 'boolean',
            'active'            => 'boolean',
            'meta_title'        => 'nullable|string|max:60',
            'meta_description'  => 'nullable|string|max:160',
        ]);
        if (empty($data['slug'])) $data['slug'] = Str::slug($data['name']);
        return $data;
    }
}
