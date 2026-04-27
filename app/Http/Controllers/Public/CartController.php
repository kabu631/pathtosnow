<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\{Product, Order, OrderItem};

class CartController extends Controller
{
    public function index()
    {
        return Inertia::render('Public/Shop/Cart', ['cartItems' => array_values(session('cart', []))]);
    }

    public function add(Request $req)
    {
        $req->validate(['product_id' => 'required|exists:products,id', 'quantity' => 'integer|min:1']);
        $product = Product::findOrFail($req->product_id);
        $cart    = session('cart', []);
        $key     = $product->id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = min($cart[$key]['quantity'] + ($req->quantity ?? 1), $product->stock);
        } else {
            $cart[$key] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'slug'     => $product->slug,
                'price'    => (float) $product->price,
                'image'    => $product->first_image,
                'quantity' => min($req->quantity ?? 1, $product->stock),
            ];
        }
        session(['cart' => $cart]);
        return back()->with('success', 'Added to cart');
    }

    public function update(Request $req)
    {
        $cart = session('cart', []);
        $id   = $req->product_id;
        if ((int) $req->quantity === 0) {
            unset($cart[$id]);
        } elseif (isset($cart[$id])) {
            $cart[$id]['quantity'] = $req->quantity;
        }
        session(['cart' => $cart]);
        return back();
    }

    public function remove(Request $req)
    {
        $cart = session('cart', []);
        unset($cart[$req->product_id]);
        session(['cart' => $cart]);
        return back();
    }

    public function clear()
    {
        session()->forget('cart');
        return back();
    }

    public function checkout(Request $req)
    {
        $req->validate([
            'name'    => 'required|string',
            'email'   => 'required|email',
            'address' => 'required|string',
            'city'    => 'required|string',
            'country' => 'required|string',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) return back()->withErrors(['cart' => 'Cart is empty']);

        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['quantity']);
        $shipping = $subtotal >= 100 ? 0 : 12.99;

        $order = Order::create([
            'user_id'              => auth()->id(),
            'customer_name'        => $req->name,
            'customer_email'       => $req->email,
            'customer_phone'       => $req->phone,
            'status'               => 'pending',
            'payment_status'       => 'unpaid',
            'payment_method'       => $req->payment_method ?? 'cod',
            'subtotal'             => $subtotal,
            'shipping_cost'        => $shipping,
            'total'                => $subtotal + $shipping,
            'shipping_name'        => $req->name,
            'shipping_address'     => $req->address,
            'shipping_city'        => $req->city,
            'shipping_country'     => $req->country,
            'shipping_postal_code' => $req->postal_code,
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id'      => $order->id,
                'product_id'    => $item['id'],
                'product_name'  => $item['name'],
                'product_image' => $item['image'],
                'quantity'      => $item['quantity'],
                'unit_price'    => $item['price'],
                'total_price'   => $item['price'] * $item['quantity'],
            ]);
            Product::where('id', $item['id'])->decrement('stock', $item['quantity']);
        }

        session()->forget('cart');
        return redirect()->route('cart.success')->with('order_number', $order->order_number);
    }

    public function success()
    {
        return Inertia::render('Public/Shop/OrderSuccess', ['orderNumber' => session('order_number')]);
    }
}
