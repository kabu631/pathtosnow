<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Package;
use App\Models\PackageType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $q     = $request->get('q', '');
        $types = $request->get('types', []);   // array of type_key strings

        $query = Location::where('is_active', true)
            ->withCount(['packages as packages_count']);

        // Search by name or description
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // Filter by package type: keep locations that have at least one package of selected type(s)
        if (!empty($types)) {
            $query->whereHas('packages', function ($sub) use ($types) {
                $sub->whereIn('type', $types);
            });
        }

        $locations = $query->orderBy('name')->get();

        // Package types for sidebar (only those that actually have packages)
        $packageTypes = PackageType::active()
            ->orderBy('sort_order')
            ->get(['id', 'name', 'type_key', 'icon_emoji']);

        return Inertia::render('Public/Locations/Index', [
            'locations'    => $locations,
            'packageTypes' => $packageTypes,
            'filters'      => ['q' => $q, 'types' => $types],
        ]);
    }

    public function show($slug)
    {
        $location = Location::where('slug', $slug)
            ->where('is_active', true)
            ->with(['images'])
            ->firstOrFail();

        $packages = Package::active()
            ->where('location_id', $location->id)
            ->orderBy('name')
            ->paginate(12);

        return Inertia::render('Public/Locations/Show', [
            'location' => $location,
            'packages' => $packages,
        ]);
    }
}
