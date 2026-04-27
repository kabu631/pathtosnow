<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\{User, Package, ItineraryDay, Post, Category, Product};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
        $admin = User::create([
            'name' => 'Admin', 'email' => 'admin@himalayatrails.com',
            'password' => Hash::make('Password'), 'role' => 'admin',
        ]);
        User::create([
            'name' => 'Ram Shrestha', 'email' => 'customer@trekbazar.com',
            'password' => Hash::make('password'), 'role' => 'customer',
        ]);

        // ── PACKAGES ─────────────────────────────────────────────
        $packages = [
            // ADVENTURE
            [
                'type' => 'adventure', 'name' => 'Bungee Jump — The Last Resort',
                'slug' => 'bungee-jump-last-resort', 'location' => 'Bhote Koshi, Sindhupalchok',
                'region' => 'Bagmati Province',
                'short_description' => '160m freefall over the Bhote Koshi gorge — one of Asia\'s highest bungee jumps.',
                'description' => '<p>The Last Resort bungee is one of Asia\'s most dramatic adventure experiences. You leap from a suspension bridge 160 metres above the raging Bhote Koshi river. No experience required — just courage.</p><p>The jump is fully supervised by experienced instructors. Includes return transport from Kathmandu, a harness fitting session, and a certificate of achievement.</p>',
                'price_per_person' => 120.00, 'duration_days' => 1, 'duration_nights' => 0,
                'difficulty' => 'easy', 'best_season' => 'Year-round',
                'highlights' => ['160m freefall', 'Gorge views', 'Certificate included', 'Transport from Kathmandu'],
                'included' => ['Return transport KTM', 'Harness & equipment', 'Safety briefing', 'Jump certificate'],
                'excluded' => ['Personal insurance', 'Meals', 'Accommodation'],
                'featured' => true, 'active' => true,
            ],
            [
                'type' => 'adventure', 'name' => 'White Water Rafting — Trishuli River',
                'slug' => 'rafting-trishuli-river', 'location' => 'Trishuli, Nuwakot',
                'region' => 'Bagmati Province',
                'short_description' => 'Grade 3–4 rapids on Nepal\'s most popular rafting river. Full day from Kathmandu.',
                'description' => '<p>The Trishuli River offers the perfect introduction to white water rafting. Grade 3–4 rapids provide genuine excitement while remaining accessible to beginners. The river cuts through green hills with views of distant Himalayan peaks.</p>',
                'price_per_person' => 55.00, 'duration_days' => 1, 'duration_nights' => 0,
                'difficulty' => 'moderate', 'best_season' => 'Oct-May',
                'highlights' => ['Grade 3–4 rapids', 'Himalayan views', 'Professional guides', 'All equipment included'],
                'included' => ['Transport from KTM', 'Rafting equipment', 'Guide', 'Lunch by the river'],
                'excluded' => ['Personal insurance', 'Alcohol', 'Tips'],
                'featured' => true, 'active' => true,
            ],
            [
                'type' => 'adventure', 'name' => 'Paragliding — Pokhara',
                'slug' => 'paragliding-pokhara', 'location' => 'Sarangkot, Pokhara',
                'region' => 'Gandaki Province',
                'short_description' => 'Soar over Phewa Lake with Annapurna panorama. Tandem flight from Sarangkot.',
                'description' => '<p>Pokhara is one of the world\'s best paragliding destinations. Your tandem flight launches from Sarangkot hill and glides over the turquoise Phewa Lake with Annapurna, Machhapuchhre, and Dhaulagiri filling the horizon. No experience required — your certified pilot does all the work.</p>',
                'price_per_person' => 90.00, 'duration_days' => 1, 'duration_nights' => 0,
                'difficulty' => 'easy', 'best_season' => 'Sep-May',
                'highlights' => ['Annapurna panorama', 'Fewa Lake views', 'Tandem with certified pilot', 'GoPro footage available'],
                'included' => ['Equipment', 'Certified pilot', 'Transport to Sarangkot'],
                'excluded' => ['Video package (extra)', 'Hotel pickup'],
                'featured' => false, 'active' => true,
            ],

            // TREKKING
            [
                'type' => 'trekking', 'name' => 'Annapurna Base Camp Trek',
                'slug' => 'annapurna-base-camp-trek', 'location' => 'Annapurna Region, Kaski',
                'region' => 'Gandaki Province',
                'short_description' => '13-day classic trek to ABC at 4,130m. Rhododendron forests, glacier amphitheatre, mountain views.',
                'description' => '<p>The Annapurna Base Camp (ABC) trek is one of Nepal\'s most popular routes for good reason. You walk through Gurung villages, dense rhododendron forests that blaze red and pink in spring, past hot springs at Jhinu, and up to a dramatic 360° amphitheatre of peaks including Annapurna I (8,091m), Machhapuchhre, and Hiunchuli.</p><p>This is a moderate trek — challenging but achievable for anyone with reasonable fitness and proper acclimatisation.</p>',
                'price_per_person' => 950.00, 'duration_days' => 13, 'duration_nights' => 12,
                'difficulty' => 'moderate', 'max_altitude_m' => 4130,
                'best_season' => 'Mar-May, Sep-Dec', 'start_point' => 'Nayapul', 'end_point' => 'Nayapul',
                'highlights' => ['4,130m base camp', 'Annapurna panorama', 'Rhododendron forests', 'Hot springs at Jhinu', 'Gurung villages'],
                'included' => ['Licensed guide', 'Porter', 'Teahouse accommodation', 'All meals (B/L/D)', 'ACAP permit', 'TIMS card'],
                'excluded' => ['International flights', 'Travel insurance', 'Personal gear', 'Tips'],
                'featured' => true, 'active' => true,
            ],
            [
                'type' => 'trekking', 'name' => 'Langtang Valley Trek',
                'slug' => 'langtang-valley-trek', 'location' => 'Langtang National Park, Rasuwa',
                'region' => 'Bagmati Province',
                'short_description' => '10-day trek through Tamang culture and high-altitude yak pastures near Tibet.',
                'description' => '<p>The Langtang Valley is one of Nepal\'s most accessible yet underrated treks. Just 68km north of Kathmandu, the valley offers stunning scenery, Tamang Buddhist culture, and views of Langtang Lirung (7,227m) without the crowds of the Annapurna or Everest regions.</p>',
                'price_per_person' => 750.00, 'duration_days' => 10, 'duration_nights' => 9,
                'difficulty' => 'moderate', 'max_altitude_m' => 3870,
                'best_season' => 'Mar-May, Oct-Nov', 'start_point' => 'Syabrubesi', 'end_point' => 'Syabrubesi',
                'highlights' => ['Tamang culture', 'Yak pastures', 'Kyanjin Gompa', 'Cheese factory visit', 'Glacier views'],
                'included' => ['Guide', 'Porter', 'Accommodation', 'All meals', 'National park permits'],
                'excluded' => ['Flights to/from Kathmandu', 'Insurance', 'Personal expenses'],
                'featured' => true, 'active' => true,
            ],

            // VALLEY VISIT
            [
                'type' => 'valley_visit', 'name' => 'Kathmandu Durbar Squares Tour',
                'slug' => 'kathmandu-durbar-squares-tour', 'location' => 'Kathmandu Valley',
                'region' => 'Bagmati Province',
                'short_description' => '2-day private guided tour of all three UNESCO-listed Durbar Squares.',
                'description' => '<p>Explore the heart of Nepal\'s ancient civilisation across three UNESCO World Heritage Durbar Squares — Kathmandu, Patan (Lalitpur), and Bhaktapur. Each square is a living museum of Newari architecture, medieval temples, royal palaces, and traditional craftwork still practiced today.</p>',
                'price_per_person' => 120.00, 'duration_days' => 2, 'duration_nights' => 1,
                'difficulty' => 'easy', 'best_season' => 'Year-round',
                'highlights' => ['3 UNESCO sites', 'Expert local guide', 'Kumari Living Goddess courtyard', 'Pashupatinath Temple', 'Boudhanath Stupa'],
                'included' => ['Private guide', 'Entry fees', 'Transport between sites', 'Lunch at Bhaktapur'],
                'excluded' => ['Hotel accommodation', 'Dinner', 'Personal shopping'],
                'featured' => true, 'active' => true,
            ],

            // NATIONAL PARK
            [
                'type' => 'national_park', 'name' => 'Chitwan Jungle Safari — 3 Days',
                'slug' => 'chitwan-jungle-safari-3-days', 'location' => 'Chitwan National Park, Nawalpur',
                'region' => 'Bagmati / Madhesh Province',
                'short_description' => 'Jeep safari, elephant breeding centre, canoe ride, and Tharu culture in Nepal\'s most famous national park.',
                'description' => '<p>Chitwan National Park is a UNESCO World Heritage Site and home to one-horned rhinoceroses, Bengal tigers, gharial crocodiles, and over 600 bird species. Our 3-day package includes jeep safaris at dawn and dusk (best for wildlife), a canoe ride on the Rapti River, cultural visits to Tharu villages, and a visit to the elephant breeding centre.</p>',
                'price_per_person' => 350.00, 'duration_days' => 3, 'duration_nights' => 2,
                'difficulty' => 'easy', 'best_season' => 'Oct-Mar',
                'highlights' => ['One-horned rhino sightings', 'Tiger territory jeep safari', 'Canoe river ride', 'Tharu cultural program', 'Bird watching'],
                'included' => ['Accommodation at jungle lodge', 'All meals', 'Jeep safaris', 'Park entry fees', 'Naturalist guide'],
                'excluded' => ['Transport to/from Chitwan', 'Alcohol', 'Tips', 'Personal insurance'],
                'featured' => true, 'active' => true,
            ],

            // WILDLIFE RESERVE
            [
                'type' => 'wildlife_reserve', 'name' => 'Bardia Wildlife Reserve — Tiger Trek',
                'slug' => 'bardia-wildlife-tiger-trek', 'location' => 'Bardia National Park, Bardiya',
                'region' => 'Lumbini Province',
                'short_description' => '5-day deep wilderness safari in Nepal\'s least-visited tiger reserve. Better odds than Chitwan.',
                'description' => '<p>Bardia National Park is Nepal\'s largest and most pristine wildlife reserve, with significantly higher tiger density than Chitwan. Because it receives far fewer visitors, wildlife encounters here feel genuinely wild. Walk with a naturalist through tall elephant grass, riverine forest, and open savannahs. Most visitors see rhino, deer, and monkeys. Tiger sightings are rare but real.</p>',
                'price_per_person' => 580.00, 'duration_days' => 5, 'duration_nights' => 4,
                'difficulty' => 'easy', 'best_season' => 'Oct-Apr',
                'highlights' => ['Best tiger chances in Nepal', 'Pristine wilderness', 'Walking safaris', 'Karnali River rafting option', 'Dolphin spotting'],
                'included' => ['Eco-lodge accommodation', 'All meals', 'Walking safaris', 'Park fees', 'Expert naturalist'],
                'excluded' => ['Flights to Nepalgunj', 'Personal insurance', 'Alcohol'],
                'featured' => false, 'active' => true,
            ],

            // LAKE
            [
                'type' => 'lake', 'name' => 'Rara Lake Trek — Nepal\'s Hidden Gem',
                'slug' => 'rara-lake-trek', 'location' => 'Rara National Park, Mugu',
                'region' => 'Karnali Province',
                'short_description' => '12-day expedition to Nepal\'s largest and most remote lake at 2,990m. True wilderness.',
                'description' => '<p>Rara Lake is Nepal\'s largest and most remote lake — a jewel of deep blue surrounded by conifer forests and snow-capped peaks in the far northwest. Because of its remoteness (flights from Kathmandu to Jumla, then trek), very few tourists ever reach it. Those who do are rewarded with absolute silence, pristine nature, and one of the most beautiful lake settings on earth.</p>',
                'price_per_person' => 1650.00, 'duration_days' => 12, 'duration_nights' => 11,
                'difficulty' => 'challenging', 'max_altitude_m' => 3731,
                'best_season' => 'Apr-Jun, Sep-Nov', 'start_point' => 'Jumla', 'end_point' => 'Jumla',
                'highlights' => ['Nepal\'s largest lake', 'Rara National Park', 'Snow leopard habitat', 'Remote villages', 'Untouched wilderness'],
                'included' => ['Domestic flights KTM-Jumla-KTM', 'Guide & porter', 'Accommodation', 'All meals', 'Park permits'],
                'excluded' => ['International flights', 'Travel insurance', 'Personal gear', 'Tips'],
                'featured' => true, 'active' => true,
            ],
            [
                'type' => 'lake', 'name' => 'Fewa Lake Pokhara — Kayaking & Reflection',
                'slug' => 'fewa-lake-pokhara-kayaking', 'location' => 'Phewa Lake, Pokhara',
                'region' => 'Gandaki Province',
                'short_description' => '2-day Pokhara experience: kayaking on Fewa, Barahi Island temple, Annapurna reflections.',
                'description' => '<p>Fewa Lake is one of Nepal\'s most beautiful bodies of water — a 4.4 km² lake with perfect reflections of the Annapurna massif on calm mornings. Kayak at sunrise, visit the Tal Barahi temple on the island, cycle the lakeside, and watch the mountains from the shore at dusk.</p>',
                'price_per_person' => 85.00, 'duration_days' => 2, 'duration_nights' => 1,
                'difficulty' => 'easy', 'best_season' => 'Sep-May',
                'highlights' => ['Annapurna reflections', 'Sunrise kayaking', 'Island temple visit', 'Lakeside cycling', 'Mountain photography'],
                'included' => ['Kayak hire', 'Guide', 'Lakeside hotel 1 night', 'Breakfast'],
                'excluded' => ['Lunch/dinner', 'Paragliding (can be added)', 'Personal transport'],
                'featured' => false, 'active' => true,
            ],
        ];

        foreach ($packages as $p) {
            $pkg = Package::create($p);

            // Add sample itinerary for trekking packages
            if ($pkg->type === 'trekking' && $pkg->slug === 'annapurna-base-camp-trek') {
                $days = [
                    [1, 'Arrival in Pokhara', 'Fly or drive from Kathmandu to Pokhara. Rest and trek briefing.', 'Hotel', ['Dinner'], null, 820, null],
                    [2, 'Nayapul to Tikhedhunga', 'Drive to Nayapul (1.5hr), begin trek through Birethanti village.', 'Guesthouse', ['Breakfast','Lunch','Dinner'], 13, 1540, 720],
                    [3, 'Tikhedhunga to Ghorepani', 'Steep climb through rhododendron forest. Sunrise views of Dhaulagiri.', 'Teahouse', ['Breakfast','Lunch','Dinner'], 12, 2874, 1334],
                    [4, 'Poon Hill Sunrise & Tadapani', 'Pre-dawn climb to Poon Hill (3,210m) for panoramic sunrise. Trek to Tadapani.', 'Teahouse', ['Breakfast','Lunch','Dinner'], 10, 2630, 336],
                    [5, 'Tadapani to Chhomrong', 'Trek through oak forests to the Gurung village of Chhomrong.', 'Teahouse', ['Breakfast','Lunch','Dinner'], 9, 2170, null],
                    [6, 'Chhomrong to Dovan', 'Enter the Annapurna Sanctuary. Bamboo and rhododendron forest.', 'Teahouse', ['Breakfast','Lunch','Dinner'], 11, 2600, 430],
                    [7, 'Dovan to Deurali', 'Trek through Himalaya Hotel and Hinko Cave area. Glacier views.', 'Teahouse', ['Breakfast','Lunch','Dinner'], 7, 3230, 630],
                    [8, 'Deurali to Annapurna Base Camp', 'Final push to the glacier amphitheatre at 4,130m. Incredible 360° views.', 'Teahouse', ['Breakfast','Lunch','Dinner'], 9, 4130, 900],
                    [9, 'ABC to Bamboo via Jhinu Hot Springs', 'Descend fast, stop at natural hot springs at Jhinu for a soak.', 'Guesthouse', ['Breakfast','Lunch','Dinner'], 16, 2310, null],
                    [10, 'Bamboo to Chhomrong to Sinuwa', 'Continue descent through familiar terrain.', 'Teahouse', ['Breakfast','Lunch','Dinner'], 10, 2360, null],
                    [11, 'Sinuwa to Nayapul', 'Long descent day. Drive to Pokhara on arrival.', 'Hotel', ['Breakfast','Lunch'], 18, 820, null],
                    [12, 'Pokhara free day', 'Rest, explore Pokhara lakeside, last shopping.', 'Hotel', ['Breakfast'], null, 820, null],
                    [13, 'Return to Kathmandu', 'Fly or drive back to Kathmandu. Tour ends.', null, ['Breakfast'], null, 1400, null],
                ];
                foreach ($days as [$day, $title, $desc, $acc, $meals, $dist, $alt, $gain]) {
                    ItineraryDay::create([
                        'package_id' => $pkg->id, 'day_number' => $day, 'title' => $title,
                        'description' => $desc, 'accommodation' => $acc, 'meals' => $meals,
                        'distance_km' => $dist, 'altitude_m' => $alt, 'elevation_gain_m' => $gain,
                    ]);
                }
            }
        }

        // ── BLOG POSTS ────────────────────────────────────────────
        $posts = [
            ['Everest Base Camp vs Annapurna Base Camp: Which is right for you?', 'blog', 'trekking', true],
            ['What to eat in Nepal: 15 dishes you must try', 'food', 'nepali-food', true],
            ['Dashain Festival 2025: Complete guide for visitors', 'festival', 'dashain', true],
            ['Kathmandu city tour: 3 days itinerary for first-timers', 'city_tour', 'kathmandu', true],
            ['Nepal travel guide: Visa, money, safety, transport', 'travel_guide', 'general', true],
            ['Newari culture: Understanding the people of Kathmandu Valley', 'culture', 'newari', false],
        ];

        foreach ($posts as [$title, $type, $cat, $published]) {
            Post::create([
                'user_id' => $admin->id,
                'title' => $title,
                'slug' => Str::slug($title),
                'excerpt' => 'A comprehensive guide for travellers visiting Nepal.',
                'content' => '<p>This is a detailed guide about ' . $title . '. Full content coming soon.</p>',
                'post_type' => $type,
                'category' => $cat,
                'tags' => ['nepal', 'travel', $type],
                'published' => $published,
                'published_at' => $published ? now() : null,
                'read_time' => rand(5, 15),
                'views' => rand(100, 2000),
            ]);
        }

        // ── GEAR CATEGORIES ───────────────────────────────────────
        $cats = [
            ['Backpacks', 'backpacks', 1],
            ['Boots & Footwear', 'boots', 2],
            ['Tents & Shelter', 'tents', 3],
            ['Clothing & Layers', 'clothing', 4],
            ['Sleeping Gear', 'sleeping', 5],
            ['Safety & First Aid', 'safety', 6],
            ['Navigation', 'navigation', 7],
            ['Accessories', 'accessories', 8],
        ];

        foreach ($cats as [$name, $slug, $sort]) {
            Category::create(['name' => $name, 'slug' => $slug, 'sort_order' => $sort]);
        }

        $backpackCat = Category::where('slug', 'backpacks')->first();
        $bootsCat    = Category::where('slug', 'boots')->first();
        $sleepCat    = Category::where('slug', 'sleeping')->first();

        // ── PRODUCTS ──────────────────────────────────────────────
        $products = [
            [$backpackCat->id, 'Osprey Atmos AG 65L', 'osprey-atmos-ag-65l', 289.99, 340.00, 15, true, ['Volume' => '65L', 'Frame' => 'Anti-Gravity']],
            [$bootsCat->id, 'Salomon X Ultra 4 Mid GTX', 'salomon-x-ultra-4-mid-gtx', 179.99, 210.00, 22, true, ['Waterproof' => 'Gore-Tex', 'Ankle' => 'Mid-cut']],
            [$sleepCat->id, 'Sea to Summit Spark SP3', 'sea-to-summit-spark-sp3', 349.00, null, 8, true, ['Rating' => '-9°C', 'Fill' => '850+ Down']],
            [$backpackCat->id, 'Deuter Speed Lite 20L', 'deuter-speed-lite-20l', 89.99, 110.00, 30, false, ['Volume' => '20L', 'Type' => 'Daypack']],
        ];

        foreach ($products as [$catId, $name, $slug, $price, $compare, $stock, $featured, $specs]) {
            Product::create([
                'category_id' => $catId, 'name' => $name, 'slug' => $slug,
                'description' => 'Professional grade gear for Himalayan trekking.',
                'price' => $price, 'compare_price' => $compare, 'stock' => $stock,
                'featured' => $featured, 'active' => true, 'specs' => $specs,
                'tags' => ['trekking', 'gear', 'nepal'],
            ]);
        }
    }
}
