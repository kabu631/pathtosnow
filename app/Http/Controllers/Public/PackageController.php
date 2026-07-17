<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\Package;
use App\Models\PackageType;

class PackageController extends Controller
{
    private function normaliseTypeSlug(string $slug): string
    {
        return [
            'wildlife'          => 'wildlife_reserve',
            'wildlife-reserve'  => 'wildlife_reserve',
            'wildlife-reserves' => 'wildlife_reserve',
            'national-park'     => 'national_park',
            'national-parks'    => 'national_park',
            'valley-visit'      => 'valley_visit',
            'valley-visits'     => 'valley_visit',
        ][$slug] ?? str_replace('-', '_', $slug);
    }

    private function applyPackageSearch($query, ?string $term)
    {
        return $query->when($term, function ($qq) use ($term) {
            $qq->where(function ($inner) use ($term) {
                $inner->where('name', 'like', "%{$term}%")
                    ->orWhere('location', 'like', "%{$term}%");
            });
        });
    }
    /** Generic handler for any package type slug — used by /packages/type/{slug} */
    public function dynamicCategory(string $slug)
    {
        $typeKey = $this->normaliseTypeSlug($slug);
        $packageType = PackageType::where('is_active', true)
            ->where(function ($query) use ($slug, $typeKey) {
                $query->where('slug', $slug)->orWhere('type_key', $typeKey);
            })
            ->firstOrFail();

        $q          = request('q');
        $difficulty = request('difficulty');
        $sort       = request('sort', 'featured');
        $sortMap    = [
            'featured'     => ['featured', 'desc'],
            'price_asc'    => ['price_per_person', 'asc'],
            'price_desc'   => ['price_per_person', 'desc'],
            'duration_asc' => ['duration_days', 'asc'],
        ];
        [$col, $dir] = $sortMap[$sort] ?? ['featured', 'desc'];

        $packages = $this->applyPackageSearch(Package::active()->ofType($packageType->type_key), $q)
            ->when($difficulty, fn($qq) => $qq->where('difficulty', $difficulty))
            ->orderBy($col, $dir)->latest()->paginate(9)->withQueryString();

        return Inertia::render('Public/Packages/Category', [
            'packages'    => $packages,
            'filters'     => compact('q', 'difficulty', 'sort'),
            'packageType' => $packageType,
        ]);
    }

    private function listPage(string $type, string $view, array $extra = [])
    {
        $q          = request('q');
        $difficulty = request('difficulty');
        $sort       = request('sort', 'featured');

        $sortMap = [
            'featured'     => ['featured', 'desc'],
            'price_asc'    => ['price_per_person', 'asc'],
            'price_desc'   => ['price_per_person', 'desc'],
            'duration_asc' => ['duration_days', 'asc'],
        ];
        [$col, $dir] = $sortMap[$sort] ?? ['featured', 'desc'];

        $packages = $this->applyPackageSearch(Package::active()->ofType($type), $q)
            ->when($difficulty, fn($qq) => $qq->where('difficulty', $difficulty))
            ->orderBy($col, $dir)->latest()->paginate(9)->withQueryString();

        return Inertia::render($view, array_merge([
            'packages' => $packages,
            'filters'  => compact('q', 'difficulty', 'sort'),
            'type'     => $type,
        ], $extra));
    }

    public function index()
    {
        $type = request('type');
        return Inertia::render('Public/Packages/Index', [
            'packages' => $this->applyPackageSearch(
                Package::active()->when($type, fn($q) => $q->ofType($type)),
                request('q')
            )
                ->orderBy('featured', 'desc')->latest()->paginate(12)->withQueryString(),
            'counts'  => Package::active()->get(['type'])->groupBy('type')->map->count(),
            'filters' => ['type' => $type, 'q' => request('q')],
        ]);
    }

    public function adventure()    { return $this->listPage('adventure',        'Public/Packages/Adventure'); }
    public function trekking()     { return $this->listPage('trekking',         'Public/Packages/Trekking'); }
    public function valleyVisit()  { return $this->listPage('valley_visit',     'Public/Packages/ValleyVisit'); }
    public function nationalParks(){ return $this->listPage('national_park',    'Public/Packages/NationalParks'); }
    public function wildlife()     { return $this->listPage('wildlife_reserve', 'Public/Packages/Wildlife'); }
    public function lakes()        { return $this->listPage('lake',             'Public/Packages/Lakes'); }

    public function show(string $slug)
    {
        $package = Package::active()->with('itineraryDays')->where('slug', $slug)->firstOrFail();
        $package->incrementViews();

        return Inertia::render('Public/Packages/Show', [
            'package'      => $package,
            'related'      => $package->related(4),
            'relatedPosts' => \App\Models\Post::published()
                ->where('related_package_id', $package->id)
                ->orWhere(function ($q) {
                    $q->where('post_type', 'travel_guide')->orWhere('post_type', 'blog');
                })
                ->limit(3)->get(['id', 'title', 'slug', 'cover_image', 'post_type', 'read_time']),
        ]);
    }
}
