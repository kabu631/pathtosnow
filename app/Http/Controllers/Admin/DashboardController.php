<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\{Package, Booking, Post, Product, Order};

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'packages'         => Package::count(),
                'bookings'         => Booking::count(),
                'pending_bookings' => Booking::where('status', 'pending')->count(),
                'posts'            => Post::count(),
                'products'         => Product::count(),
                'orders'           => Order::count(),
                'revenue_30d'      => Booking::where('created_at', '>=', now()->subDays(30))
                    ->whereIn('status', ['confirmed', 'in_progress', 'completed'])->sum('total_price'),
                'shop_revenue_30d' => Order::where('created_at', '>=', now()->subDays(30))
                    ->whereIn('status', ['confirmed', 'shipped', 'delivered'])->sum('total'),
            ],
            'recentBookings' => Booking::with('package:id,name,type')->latest()->limit(8)
                ->get(['id', 'booking_reference', 'customer_name', 'total_price', 'status', 'travel_date', 'created_at']),
            'bookingsByType' => Booking::join('packages', 'packages.id', '=', 'bookings.package_id')
                ->selectRaw('packages.type, count(*) as count')->groupBy('packages.type')->pluck('count', 'type'),
        ]);
    }
}
