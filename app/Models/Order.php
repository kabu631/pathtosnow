<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'customer_name', 'customer_email', 'customer_phone',
        'status', 'payment_status', 'payment_method', 'paid_at',
        'subtotal', 'shipping_cost', 'total', 'currency',
        'shipping_name', 'shipping_address', 'shipping_city', 'shipping_country', 'shipping_postal_code',
        'notes', 'shipped_at', 'delivered_at',
    ];

    protected $casts = [
        'shipped_at'    => 'datetime',
        'delivered_at'  => 'datetime',
        'paid_at'       => 'datetime',
        'subtotal'      => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total'         => 'decimal:2',
    ];

    public function user()  { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(OrderItem::class); }

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->order_number = 'TB-SHOP-' . strtoupper(Str::random(6)));
    }
}
