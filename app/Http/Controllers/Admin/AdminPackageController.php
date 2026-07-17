<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Package;
use App\Models\PackageType;
use App\Models\Country;
use App\Services\ImageService;

class AdminPackageController extends Controller
{
    const ABROAD_TYPES = [
        'tour'        => 'Tour',
        'adventure'   => 'Adventure',
        'cultural'    => 'Cultural',
        'pilgrimage'  => 'Pilgrimage',
        'wildlife'    => 'Wildlife',
        'cruise'      => 'Cruise',
        'trekking'    => 'Trekking',
    ];

    public function index()
    {
        $isAbroad = request()->boolean('abroad');

        return Inertia::render('Admin/Packages/Index', [
            'packages' => Package::when(request('type'), fn($q, $t) => $q->ofType($t))
                ->when(request('q'), fn($q, $s) => $q->where('name', 'like', "%$s%"))
                ->when($isAbroad, fn($q) => $q->whereNotNull('country_id'))
                ->when(!$isAbroad, fn($q) => $q->whereNull('country_id'))
                ->with('country:id,name')
                ->withCount('bookings')
                ->orderBy('type')->latest()->paginate(15)->withQueryString(),
            'types' => PackageType::orderBy('sort_order')->pluck('name', 'type_key'),
            'is_abroad' => $isAbroad,
        ]);
    }

    public function create()
    {
        $isAbroad = request()->boolean('abroad');
        return Inertia::render('Admin/Packages/Form', [
            'package'    => null,
            'types'      => $isAbroad ? self::ABROAD_TYPES : PackageType::active()->orderBy('sort_order')->pluck('name', 'type_key'),
            'countries'  => Country::active()->orderBy('name')->pluck('name', 'id'),
            'locations'  => \App\Models\Location::where('is_active', true)->orderBy('name')->pluck('name', 'id'),
            'is_abroad'  => $isAbroad,
        ]);
    }

    public function store(Request $req)
    {
        $data = $this->validatePkg($req);

        if ($req->hasFile('cover_image_file')) {
            $data['cover_image'] = ImageService::store($req->file('cover_image_file'), 'packages');
        }

        $gallery = [];
        if ($req->hasFile('gallery_files')) {
            foreach ($req->file('gallery_files') as $file) {
                $gallery[] = ImageService::store($file, 'packages/gallery');
            }
        }
        $data['gallery'] = $gallery;

        Package::create($data);
        return redirect()->route('admin.packages.index')->with('success', 'Package created!');
    }

    public function edit(Package $package)
    {
        $isAbroad = $package->country_id !== null;
        return Inertia::render('Admin/Packages/Form', [
            'package'   => $package->load('itineraryDays'),
            'types'     => $isAbroad ? self::ABROAD_TYPES : PackageType::active()->orderBy('sort_order')->pluck('name', 'type_key'),
            'countries' => Country::active()->orderBy('name')->pluck('name', 'id'),
            'locations'  => \App\Models\Location::where('is_active', true)->orderBy('name')->pluck('name', 'id'),
            'is_abroad'  => $isAbroad,
        ]);
    }

    public function update(Request $req, Package $package)
    {
        $data = $this->validatePkg($req, $package);

        if ($req->hasFile('cover_image_file')) {
            if ($package->cover_image) {
                ImageService::delete($package->cover_image);
            }
            $data['cover_image'] = ImageService::store($req->file('cover_image_file'), 'packages');
        }

        $retainedGallery = $req->input('gallery', []);
        $existingGallery = $package->gallery ?? [];
        $deletedGallery = array_diff($existingGallery, $retainedGallery);
        foreach ($deletedGallery as $delImg) {
            ImageService::delete($delImg);
        }

        if ($req->hasFile('gallery_files')) {
            foreach ($req->file('gallery_files') as $file) {
                $retainedGallery[] = ImageService::store($file, 'packages/gallery');
            }
        }
        $data['gallery'] = $retainedGallery;

        $package->update($data);
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
        $isAbroad = !empty($req->country_id);
        $typeRule = $isAbroad
            ? ['nullable', Rule::in(array_keys(self::ABROAD_TYPES))]
            : ['required', Rule::in(PackageType::pluck('type_key')->toArray())];

        $data = $req->validate([
            'type'              => $typeRule,
            'country_id'        => $isAbroad ? 'required|exists:countries,id' : 'nullable|exists:countries,id',
            'location_id'       => 'nullable|exists:locations,id',
            'name'              => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255',
            'location'          => 'required|string|max:255',
            'region'            => 'nullable|string|max:255',
            'short_description' => 'required|string|max:500',
            'description'       => 'required|string',
            'cover_image'       => 'nullable|string',
            'cover_image_file'  => 'nullable|image|max:5120',
            'gallery'           => 'nullable|array',
            'gallery_files'     => 'nullable|array',
            'gallery_files.*'   => 'image|max:5120',
            'price_per_person'  => 'required|numeric|min:0',
            'price_nrs'         => 'nullable|numeric|min:0',
            'original_price'    => 'nullable|numeric|min:0',
            'original_price_nrs'=> 'nullable|numeric|min:0',
            'discount_label'    => 'nullable|string|max:100',
            'is_special_offer'  => 'boolean',
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
