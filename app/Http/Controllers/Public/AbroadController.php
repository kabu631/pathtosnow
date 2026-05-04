<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Inertia\Inertia;

class AbroadController extends Controller
{
    public function show($slug)
    {
        $country = Country::where('slug', $slug)->active()->firstOrFail();
        
        $packages = $country->packages()
            ->active()
            ->orderBy('featured', 'desc')
            ->latest()
            ->paginate(12);

        return Inertia::render('Public/Abroad/Show', [
            'country' => $country,
            'packages' => $packages,
        ]);
    }
}
