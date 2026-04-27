<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model
{
    protected $fillable = [
        'slug', 'title', 'content', 'meta_description', 'show_in_footer', 'sort_order',
    ];

    protected $casts = [
        'show_in_footer' => 'boolean',
    ];
}
