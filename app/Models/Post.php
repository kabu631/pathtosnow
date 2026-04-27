<?php
// app/Models/Post.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'user_id','title','slug','excerpt','content','cover_image','post_type',
        'category','tags','published','published_at','views','read_time',
        'meta_title','meta_description','related_package_id',
    ];

    protected $casts = [
        'tags'         => 'array',
        'published'    => 'boolean',
        'published_at' => 'datetime',
    ];

    const TYPE_LABELS = [
        'blog'          => 'Blog',
        'food'          => 'Food & Drink',
        'culture'       => 'Culture',
        'festival'      => 'Festivals',
        'city_tour'     => 'City Tour',
        'travel_guide'  => 'Travel Guide',
    ];

    const TYPE_COLORS = [
        'blog'         => 'blue',
        'food'         => 'amber',
        'culture'      => 'purple',
        'festival'     => 'coral',
        'city_tour'    => 'teal',
        'travel_guide' => 'green',
    ];

    public function author()         { return $this->belongsTo(User::class, 'user_id'); }
    public function relatedPackage() { return $this->belongsTo(Package::class, 'related_package_id'); }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('published', true)->whereNotNull('published_at');
    }

    public function related(int $limit = 3): \Illuminate\Database\Eloquent\Collection
    {
        return static::published()
            ->where('post_type', $this->post_type)
            ->where('id', '!=', $this->id)
            ->latest('published_at')
            ->limit($limit)
            ->get(['id','title','slug','cover_image','post_type','read_time','published_at']);
    }

    public function incrementViews(): void { $this->increment('views'); }

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->slug = $m->slug ?: Str::slug($m->title));
    }
}
