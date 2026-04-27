<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PostType extends Model
{
    protected $fillable = ['name','slug','type_key','icon_emoji','hero_image_url','color','sort_order','is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($q) { return $q->where('is_active', true); }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($m) {
            if (!$m->slug)     $m->slug     = Str::slug($m->name);
            if (!$m->type_key) $m->type_key = Str::snake($m->name);
        });
    }
}
