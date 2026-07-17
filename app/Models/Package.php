<?php
// app/Models/Package.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Package extends Model
{
    protected $fillable = [
        'country_id','location_id','type','name','slug','location','region','short_description','description',
        'cover_image','gallery','price_per_person','price_nrs','price_group','duration_days',
        'duration_nights','min_group_size','max_group_size','difficulty','max_altitude_m',
        'best_season','start_point','end_point','highlights','included','excluded',
        'requirements','faqs','featured','active','views','meta_title','meta_description',
        'original_price','original_price_nrs','discount_label','is_special_offer',
    ];

    protected $casts = [
        'gallery' => 'array', 'highlights' => 'array', 'included' => 'array',
        'excluded' => 'array', 'requirements' => 'array', 'faqs' => 'array',
        'featured' => 'boolean', 'active' => 'boolean', 'is_special_offer' => 'boolean',
        'price_per_person' => 'decimal:2', 'price_nrs' => 'decimal:2', 'price_group' => 'decimal:2',
        'original_price' => 'decimal:2', 'original_price_nrs' => 'decimal:2',
    ];

    // Type labels for display
    const TYPE_LABELS = [
        'adventure'        => 'Adventure',
        'valley_visit'     => 'Valley Visit',
        'trekking'         => 'Trekking',
        'national_park'    => 'National Park',
        'wildlife_reserve' => 'Wildlife Reserve',
        'lake'             => 'Lakes',
    ];

    const TYPE_ICONS = [
        'adventure'        => 'zap',
        'valley_visit'     => 'landmark',
        'trekking'         => 'mountain',
        'national_park'    => 'trees',
        'wildlife_reserve' => 'paw',
        'lake'             => 'waves',
    ];

    public function itineraryDays()
    {
        return $this->hasMany(ItineraryDay::class)->orderBy('day_number');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'related_package_id');
    }

    public function scopeActive(Builder $q): Builder { return $q->where('active', true); }
    public function scopeFeatured(Builder $q): Builder { return $q->where('featured', true); }
    public function scopeOfType(Builder $q, string $type): Builder { return $q->where('type', $type); }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPE_LABELS[$this->type] ?? ucfirst($this->type);
    }

    public function related(int $limit = 4): \Illuminate\Database\Eloquent\Collection
    {
        return static::active()
            ->where('type', $this->type)
            ->where('id', '!=', $this->id)
            ->inRandomOrder()
            ->limit($limit)
            ->get(['id','type','name','slug','location','price_per_person','price_nrs','duration_days','cover_image','difficulty']);
    }

    public function incrementViews(): void { $this->increment('views'); }

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->slug = $m->slug ?: Str::slug($m->name));
    }
}
