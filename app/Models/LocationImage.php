<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocationImage extends Model
{
    protected $fillable = ['location_id', 'image_path', 'caption', 'sort_order'];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
