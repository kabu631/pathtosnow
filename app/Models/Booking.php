<?php
// app/Models/Booking.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $fillable = [
        'package_id','custom_package_name','user_id','booking_reference','is_quotation','customer_name','customer_email',
        'customer_phone','customer_nationality','travel_date','group_size',
        'special_requests','emergency_contact_name','emergency_contact_phone',
        'price_per_person','total_price','currency','status','admin_notes',
        'confirmed_at','cancelled_at','cancellation_reason',
    ];

    protected $casts = [
        'is_quotation'   => 'boolean',
        'travel_date'    => 'date',
        'confirmed_at'   => 'datetime',
        'cancelled_at'   => 'datetime',
        'price_per_person' => 'decimal:2',
        'total_price'    => 'decimal:2',
    ];

    const STATUS_COLORS = [
        'pending'     => 'amber',
        'confirmed'   => 'blue',
        'in_progress' => 'purple',
        'completed'   => 'green',
        'cancelled'   => 'red',
    ];

    public function package() { return $this->belongsTo(Package::class); }
    public function user()    { return $this->belongsTo(User::class); }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'gray';
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($b) {
            $b->booking_reference = 'TB-' . strtoupper(date('Ymd')) . '-' . strtoupper(Str::random(4));
        });
    }
}
