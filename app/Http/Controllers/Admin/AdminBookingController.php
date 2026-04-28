<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Services\MailService;

class AdminBookingController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Bookings/Index', [
            'bookings' => Booking::with('package:id,name,type,slug')
                ->when(request('status'), fn($q, $s) => $q->where('status', $s))
                ->when(request('type'),   fn($q, $t) => $q->whereHas('package', fn($q2) => $q2->where('type', $t)))
                ->when(request('q'),      fn($q, $s) => $q->where('customer_name', 'like', "%$s%")
                    ->orWhere('customer_email', 'like', "%$s%")
                    ->orWhere('booking_reference', 'like', "%$s%"))
                ->latest()->paginate(20)->withQueryString(),
            'filters' => request()->only(['status', 'type', 'q']),
            'types'   => \App\Models\Package::TYPE_LABELS,
        ]);
    }

    public function show(Booking $booking)
    {
        return Inertia::render('Admin/Bookings/Show', [
            'booking' => $booking->load('package:id,name,slug,type,location,cover_image', 'user:id,name,email'),
        ]);
    }

    public function updateStatus(Request $req, Booking $booking)
    {
        $req->validate(['status' => 'required|in:pending,confirmed,in_progress,completed,cancelled']);
        $booking->update([
            'status'              => $req->status,
            'confirmed_at'        => $req->status === 'confirmed' ? now() : $booking->confirmed_at,
            'cancelled_at'        => $req->status === 'cancelled' ? now() : $booking->cancelled_at,
            'cancellation_reason' => $req->status === 'cancelled' ? $req->reason : $booking->cancellation_reason,
        ]);
        $booking->load('package');
        app(MailService::class)->sendBookingStatusUpdate($booking);
        return back()->with('success', "Booking status updated to {$req->status}");
    }

    public function updateNotes(Request $req, Booking $booking)
    {
        $req->validate(['admin_notes' => 'nullable|string|max:2000']);
        $booking->update(['admin_notes' => $req->admin_notes]);
        return back()->with('success', 'Notes saved.');
    }

    public function destroy(Booking $booking)
    {
        $ref = $booking->booking_reference;
        $booking->delete();
        return redirect('/admin/bookings')->with('success', "Booking {$ref} deleted.");
    }
}
