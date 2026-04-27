<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PackageType;
use App\Models\PostType;

class TypesSeeder extends Seeder
{
    public function run(): void
    {
        // ── Package Types ──────────────────────────────────────────────
        $packageTypes = [
            [
                'name'          => 'Adventure',
                'slug'          => 'adventure',
                'type_key'      => 'adventure',
                'description'   => 'Bungee jumping, white-water rafting, paragliding & extreme Nepal experiences',
                'icon_emoji'    => '⚡',
                'hero_image_url'=> 'https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?w=1600&q=80&auto=format&fit=crop',
                'gradient'      => 'from-red-700 to-orange-700',
                'badge_class'   => 'bg-red-100 text-red-700',
                'sort_order'    => 1,
            ],
            [
                'name'          => 'Trekking',
                'slug'          => 'trekking',
                'type_key'      => 'trekking',
                'description'   => "Annapurna, Everest Base Camp, Langtang & Nepal's greatest trails",
                'icon_emoji'    => '🏔',
                'hero_image_url'=> 'https://images.unsplash.com/photo-1467887913518-98ca7ce13ede?w=1600&q=80&auto=format&fit=crop',
                'gradient'      => 'from-emerald-800 to-green-700',
                'badge_class'   => 'bg-emerald-100 text-emerald-800',
                'sort_order'    => 2,
            ],
            [
                'name'          => 'Valley Visit',
                'slug'          => 'valley-visit',
                'type_key'      => 'valley_visit',
                'description'   => "Kathmandu, Pokhara, Bhaktapur & Nepal's UNESCO World Heritage Sites",
                'icon_emoji'    => '🏛',
                'hero_image_url'=> 'https://images.unsplash.com/photo-1605640840605-14ac1855827b?w=1600&q=80&auto=format&fit=crop',
                'gradient'      => 'from-violet-800 to-purple-700',
                'badge_class'   => 'bg-violet-100 text-violet-700',
                'sort_order'    => 3,
            ],
            [
                'name'          => 'National Park',
                'slug'          => 'national-park',
                'type_key'      => 'national_park',
                'description'   => "Chitwan, Sagarmatha, Langtang & Nepal's protected natural wonders",
                'icon_emoji'    => '🌿',
                'hero_image_url'=> 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=1600&q=80&auto=format&fit=crop',
                'gradient'      => 'from-green-800 to-teal-700',
                'badge_class'   => 'bg-green-100 text-green-800',
                'sort_order'    => 4,
            ],
            [
                'name'          => 'Wildlife Reserve',
                'slug'          => 'wildlife-reserve',
                'type_key'      => 'wildlife_reserve',
                'description'   => "Bardia, Parsa & Nepal's remote wildlife sanctuaries",
                'icon_emoji'    => '🐅',
                'hero_image_url'=> 'https://images.unsplash.com/photo-1564760055775-d63b17a55c44?w=1600&q=80&auto=format&fit=crop',
                'gradient'      => 'from-amber-700 to-yellow-700',
                'badge_class'   => 'bg-amber-100 text-amber-800',
                'sort_order'    => 5,
            ],
            [
                'name'          => 'Lakes',
                'slug'          => 'lakes',
                'type_key'      => 'lake',
                'description'   => "Rara, Phewa, Begnas & Nepal's stunning high-altitude mountain lakes",
                'icon_emoji'    => '🏞',
                'hero_image_url'=> 'https://images.unsplash.com/photo-1606210695818-fbe9f66e7df0?w=1600&q=80&auto=format&fit=crop',
                'gradient'      => 'from-sky-800 to-cyan-700',
                'badge_class'   => 'bg-sky-100 text-sky-700',
                'sort_order'    => 6,
            ],
        ];

        foreach ($packageTypes as $pt) {
            PackageType::firstOrCreate(['type_key' => $pt['type_key']], $pt);
        }

        // ── Post Types ─────────────────────────────────────────────────
        $postTypes = [
            ['name' => 'Blog',          'slug' => 'blog',          'type_key' => 'blog',         'icon_emoji' => '✍️', 'color' => 'blue',   'sort_order' => 1],
            ['name' => 'Food & Drink',  'slug' => 'food-drink',    'type_key' => 'food',         'icon_emoji' => '🍜', 'color' => 'amber',  'sort_order' => 2],
            ['name' => 'Culture',       'slug' => 'culture',       'type_key' => 'culture',      'icon_emoji' => '🎭', 'color' => 'purple', 'sort_order' => 3],
            ['name' => 'Festivals',     'slug' => 'festivals',     'type_key' => 'festival',     'icon_emoji' => '🎆', 'color' => 'rose',   'sort_order' => 4],
            ['name' => 'City Tour',     'slug' => 'city-tour',     'type_key' => 'city_tour',    'icon_emoji' => '🏙', 'color' => 'teal',   'sort_order' => 5],
            ['name' => 'Travel Guide',  'slug' => 'travel-guide',  'type_key' => 'travel_guide', 'icon_emoji' => '🗺', 'color' => 'green',  'sort_order' => 6],
        ];

        foreach ($postTypes as $pt) {
            PostType::firstOrCreate(['type_key' => $pt['type_key']], $pt);
        }
    }
}
