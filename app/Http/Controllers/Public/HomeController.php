<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\{Package, Post, Product, Slide};

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Public/Home', [
            'featuredPackages' => Package::active()->featured()
                ->select('id','type','name','slug','location','price_per_person','duration_days','cover_image','difficulty','short_description','location_id')
                ->with('location:id,name')
                ->latest()->limit(6)->get(),
            'packageCounts' => [
                'adventure'        => Package::active()->ofType('adventure')->count(),
                'trekking'         => Package::active()->ofType('trekking')->count(),
                'valley_visit'     => Package::active()->ofType('valley_visit')->count(),
                'national_park'    => Package::active()->ofType('national_park')->count(),
                'wildlife_reserve' => Package::active()->ofType('wildlife_reserve')->count(),
                'lake'             => Package::active()->ofType('lake')->count(),
            ],
            'latestPosts' => Post::published()->with('author:id,name')
                ->latest('published_at')->limit(3)
                ->get(['id','title','slug','cover_image','post_type','read_time','published_at']),
            'featuredProducts' => Product::active()->featured()->with('category:id,name,slug')
                ->limit(4)->get(['id','name','slug','price','compare_price','images','category_id']),
            'slides' => Slide::where('is_active', true)->orderBy('sort_order')->get(),
            'testimonials' => \App\Models\Testimonial::where('is_active', true)->get(),
            'specialOffers' => Package::active()
                ->where('is_special_offer', true)
                ->select('id','type','name','slug','location','price_per_person','price_nrs','original_price','original_price_nrs','discount_label','duration_days','cover_image','difficulty')
                ->latest()->limit(3)->get(),
            'locations' => \App\Models\Location::where('is_active', true)->withCount('packages')->latest()->limit(6)->get()
        ]);
    }
}
