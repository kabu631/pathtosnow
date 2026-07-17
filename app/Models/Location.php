<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Location extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'cover_image', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($location) {
            if (empty($location->slug)) {
                $location->slug = Str::slug($location->name);
            }
        });
    }

    public function images(): HasMany
    {
        return $this->hasMany(LocationImage::class)->orderBy('sort_order')->orderBy('created_at', 'desc');
    }

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class)->where('active', true);
    }
}
