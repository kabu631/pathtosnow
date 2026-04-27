<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Orders/Index', [
            'orders' => Order::with('items')
                ->when(request('status'),  fn($q, $s) => $q->where('status', $s))
                ->when(request('payment'), fn($q, $p) => $q->where('payment_status', $p))
                ->latest()->paginate(20),
        ]);
    }

    public function show(Order $order)
    {
        return Inertia::render('Admin/Orders/Show', [
            'order' => $order->load('items', 'user:id,name,email'),
        ]);
    }

    public function updateStatus(Request $req, Order $order)
    {
        $req->validate(['status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded']);

        $order->update([
            'status'       => $req->status,
            'shipped_at'   => $req->status === 'shipped'   ? now() : $order->shipped_at,
            'delivered_at' => $req->status === 'delivered' ? now() : $order->delivered_at,
        ]);

        return back()->with('success', 'Order status updated to ' . ucfirst($req->status) . '.');
    }

    public function updatePayment(Request $req, Order $order)
    {
        $req->validate(['payment_status' => 'required|in:unpaid,paid,refunded']);

        $order->update([
            'payment_status' => $req->payment_status,
            'paid_at'        => $req->payment_status === 'paid' ? now() : $order->paid_at,
        ]);

        return back()->with('success', 'Payment status updated to ' . ucfirst($req->payment_status) . '.');
    }
}
