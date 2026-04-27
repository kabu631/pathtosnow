<?php
// app/Http/Middleware/HandleInertiaRequests.php
namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\{Booking, Package, ContactMessage};

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? [
                    'id'    => $request->user()->id,
                    'name'  => $request->user()->name,
                    'email' => $request->user()->email,
                    'role'  => $request->user()->role,
                ] : null,
            ],
            'flash' => [
                'success' => session('success'),
                'error'   => session('error'),
            ],
            'cartItems' => array_values(session('cart', [])),
            'cartCount' => collect(session('cart', []))->sum('quantity'),
            // Admin badge: pending bookings
            'pendingBookings' => $request->user()?->isAdmin()
                ? Booking::where('status','pending')->count()
                : 0,
            // Admin badge: unread contact messages
            'unreadContactCount' => $request->user()?->isAdmin()
                ? ContactMessage::where('status','unread')->count()
                : 0,
            // Dynamic package types for navbar
            'navPackageTypes' => cache()->remember('nav_pkg_types', 300, fn() =>
                \App\Models\PackageType::active()->orderBy('sort_order')->get(['name', 'slug', 'icon_emoji'])
            ),
            // Dynamic post types for navbar
            'navPostTypes' => cache()->remember('nav_post_types', 300, fn() =>
                \App\Models\PostType::active()->orderBy('sort_order')->get(['name', 'slug', 'icon_emoji'])
            ),
            // Keep counts for legacy components temporarily
            'packageTypeCounts' => cache()->remember('pkg_type_counts', 300, fn() =>
                Booking::get(['id'])->count() > 0 ? Package::active()->get(['type'])->groupBy('type')->map->count() : []
            ),
        ];
    }
}