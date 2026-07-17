<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\{Package, Booking};
use App\Services\MailService;

class BookingController extends Controller
{
    public function create(Package $package)
    {
        abort_if(!$package->active, 404);

        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admins cannot book packages.');
        }

        return Inertia::render('Public/Packages/Booking', [
            'package' => $package->only('id','name','slug','type','location','price_per_person',
                'duration_days','cover_image','min_group_size','max_group_size','included','excluded'),
            'user' => auth()->user()?->only('name','email','phone','nationality'),
            'isQuote' => request()->boolean('quote'),
        ]);
    }

    public function store(Request $req, Package $package)
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Admins cannot book packages.');
        }

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
            'is_quotation'            => 'nullable|boolean',
        ]);

        $total = $package->price_per_person * $data['group_size'];

        $booking = Booking::create(array_merge($data, [
            'package_id'       => $package->id,
            'user_id'          => auth()->id(),
            'price_per_person' => $package->price_per_person,
            'total_price'      => $total,
            'currency'         => 'USD',
            'status'           => 'pending',
            'is_quotation'     => $req->boolean('is_quotation'),
        ]));

        $booking->load('package');
        app(MailService::class)->sendBookingConfirmation($booking);
        app(MailService::class)->sendAdminBookingAlert($booking);

        return redirect()->route('bookings.success', $booking->booking_reference)
            ->with('booking_ref', $booking->booking_reference);
    }

    public function storeCustomQuote(Request $req)
    {
        $data = $req->validate([
            'custom_package_name'     => 'required|string|max:255',
            'customer_name'           => 'required|string|max:255',
            'customer_email'          => 'required|email',
            'customer_phone'          => 'nullable|string|max:30',
            'customer_nationality'    => 'nullable|string|max:100',
            'travel_date'             => 'required|date|after:today',
            'group_size'              => 'required|integer|min:1|max:100',
            'special_requests'        => 'nullable|string|max:1000',
        ]);

        $booking = Booking::create(array_merge($data, [
            'package_id'       => null,
            'user_id'          => auth()->id(),
            'price_per_person' => 0.00,
            'total_price'      => 0.00,
            'currency'         => 'USD',
            'status'           => 'pending',
            'is_quotation'     => true,
        ]));

        app(MailService::class)->sendBookingConfirmation($booking);
        app(MailService::class)->sendAdminBookingAlert($booking);

        return redirect()->route('bookings.success', $booking->booking_reference)
            ->with('booking_ref', $booking->booking_reference);
    }

    public function success(string $reference)
    {
        $booking = Booking::with('package:id,name,slug,cover_image,type,location')
            ->where('booking_reference', $reference)->firstOrFail();

        // Access control:
        // 1. Guest who just completed this booking (reference stored in session)
        // 2. Logged-in user who owns this booking
        // 3. Admin
        $hasAccess = session('booking_ref') === $reference
            || (auth()->check() && (
                $booking->user_id === auth()->id()
                || auth()->user()->isAdmin()
            ));

        abort_unless($hasAccess, 403, 'You do not have access to this booking.');

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
