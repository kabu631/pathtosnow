<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\{Package, Booking};

class BookingController extends Controller
{
    public function create(Package $package)
    {
        abort_if(!$package->active, 404);
        return Inertia::render('Public/Packages/Booking', [
            'package' => $package->only('id','name','slug','type','location','price_per_person',
                'duration_days','cover_image','min_group_size','max_group_size','included','excluded'),
            'user' => auth()->user()?->only('name','email','phone','nationality'),
        ]);
    }

    public function store(Request $req, Package $package)
    {
        $data = $req->validate([
            'customer_name'           => 'required|string|max:255',
            'customer_email'          => 'required|email',
            'customer_phone'          => 'nullable|string|max:30',
            'customer_nationality'    => 'nullable|string|max:100',
            'travel_date'             => 'required|date|after:today',
            'group_size'              => 'required|integer|min:'.$package->min_group_size.'|max:'.$package->max_group_size,
            'special_requests'        => 'nullable|string|max:1000',
            'emergency_contact_name'  => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:30',
        ]);

        $total = $package->price_per_person * $data['group_size'];

        $booking = Booking::create(array_merge($data, [
            'package_id'       => $package->id,
            'user_id'          => auth()->id(),
            'price_per_person' => $package->price_per_person,
            'total_price'      => $total,
            'currency'         => 'USD',
            'status'           => 'pending',
        ]));

        return redirect()->route('bookings.success', $booking->booking_reference)
            ->with('booking_ref', $booking->booking_reference);
    }

    public function success(string $reference)
    {
        $booking = Booking::with('package:id,name,slug,cover_image,type,location')
            ->where('booking_reference', $reference)->firstOrFail();
        return Inertia::render('Public/Packages/BookingSuccess', ['booking' => $booking]);
    }

    public function myBookings()
    {
        return Inertia::render('Public/MyBookings', [
            'bookings' => Booking::with('package:id,name,slug,cover_image,type')
                ->where('user_id', auth()->id())
                ->latest()->paginate(10),
        ]);
    }
}
