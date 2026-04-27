<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'price', 'compare_price',
        'images', 'stock', 'sku', 'weight_grams', 'tags', 'specs', 'featured', 'active',
        'meta_title', 'meta_description',
    ];

    protected $casts = [
        'images'        => 'array',
        'tags'          => 'array',
        'specs'         => 'array',
        'featured'      => 'boolean',
        'active'        => 'boolean',
        'price'         => 'decimal:2',
        'compare_price' => 'decimal:2',
    ];

    public function category()   { return $this->belongsTo(Category::class); }
    public function orderItems() { return $this->hasMany(OrderItem::class); }

    public function scopeActive($q)   { return $q->where('active', true); }
    public function scopeFeatured($q) { return $q->where('featured', true); }

    public function getFirstImageAttribute(): ?string { return $this->images[0] ?? null; }

    public function getDiscountPctAttribute(): int
    {
        if (!$this->compare_price || $this->compare_price <= $this->price) return 0;
        return (int) round(($this->compare_price - $this->price) / $this->compare_price * 100);
    }
}
