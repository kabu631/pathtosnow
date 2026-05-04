<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\{
    User, Country, PackageType, Package, ItineraryDay,
    Booking, PostType, Post, Category, Product, Order, OrderItem,
    ContactMessage, GalleryAlbum, GalleryImage, Slide, StaticPage
};

class AdminTestDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->seedUser();
        $this->seedPostType();
        $this->seedPackageType();
        $this->seedCountry();
        $this->seedPackage();
        $this->seedItineraryDays();
        $this->seedBooking();
        $this->seedPost();
        $this->seedCategory();
        $this->seedProduct();
        $this->seedOrder();
        $this->seedContactMessage();
        $this->seedGallery();
        $this->seedSlide();
        $this->seedStaticPage();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅  AdminTestDataSeeder completed — all 15 admin functions seeded.');
    }

    // ── 1. USER ──────────────────────────────────────────────────────────────
    private function seedUser(): void
    {
        User::firstOrCreate(
            ['email' => 'testuser@pathtosnow.com'],
            [
                'name'     => 'Test Traveller',
                'password' => Hash::make('Password@123'),
                'role'     => 'customer',
            ]
        );
        $this->command->line('  [1/15] User created/found: testuser@pathtosnow.com');
    }

    // ── 2. POST TYPE ─────────────────────────────────────────────────────────
    private function seedPostType(): void
    {
        PostType::firstOrCreate(
            ['type_key' => 'adventure_story'],
            [
                'name'       => 'Adventure Story',
                'slug'       => 'adventure-story',
                'icon_emoji' => '🧗',
                'color'      => 'orange',
                'sort_order' => 10,
                'is_active'  => true,
            ]
        );
        $this->command->line('  [2/15] PostType created/found: adventure_story');
    }

    // ── 3. PACKAGE TYPE ──────────────────────────────────────────────────────
    private function seedPackageType(): void
    {
        PackageType::firstOrCreate(
            ['type_key' => 'cultural_tour'],
            [
                'name'        => 'Cultural Tour',
                'slug'        => 'cultural-tour',
                'description' => 'Immersive cultural tours across Nepal and the Himalayas.',
                'icon_emoji'  => '🏛️',
                'gradient'    => 'from-rose-600 to-pink-700',
                'badge_class' => 'bg-rose-100 text-rose-700',
                'sort_order'  => 9,
                'is_active'   => true,
            ]
        );
        $this->command->line('  [3/15] PackageType created/found: cultural_tour');
    }

    // ── 4. COUNTRY ───────────────────────────────────────────────────────────
    private function seedCountry(): void
    {
        Country::firstOrCreate(
            ['slug' => 'india'],
            [
                'name'        => 'India',
                'description' => 'Discover the spiritual heartland of South Asia — from the Himalayas to ancient temples.',
                'cover_image' => null,
                'is_active'   => true,
            ]
        );
        $this->command->line('  [4/15] Country created/found: India');
    }

    // ── 5. PACKAGE ───────────────────────────────────────────────────────────
    private function seedPackage(): void
    {
        $country = Country::where('slug', 'india')->first();

        Package::firstOrCreate(
            ['slug' => 'test-golden-triangle-cultural-tour'],
            [
                'country_id'        => $country?->id,
                'type'              => 'cultural_tour',
                'name'              => 'Test — Golden Triangle Cultural Tour',
                'location'          => 'Delhi, Agra, Jaipur',
                'region'            => 'North India',
                'short_description' => 'A 7-day journey through India\'s most iconic cultural landmarks.',
                'description'       => '<p>Experience the Golden Triangle — Delhi, Agra, and Jaipur — on this immersive cultural tour packed with history, architecture, and authentic local cuisine.</p>',
                'cover_image'       => null,
                'gallery'           => [],
                'price_per_person'  => 45000.00,
                'price_group'       => null,
                'duration_days'     => 7,
                'duration_nights'   => 6,
                'min_group_size'    => 2,
                'max_group_size'    => 12,
                'difficulty'        => 'easy',
                'max_altitude_m'    => 216,
                'best_season'       => 'October to March',
                'start_point'       => 'New Delhi Airport',
                'end_point'         => 'Jaipur Airport',
                'highlights'        => ['Taj Mahal sunrise visit', 'Amber Fort elephant ride', 'Qutb Minar tour', 'Street food walk in Old Delhi'],
                'included'          => ['Hotel accommodation (6 nights)', 'All transfers by AC vehicle', 'English-speaking guide', 'Entry fees to monuments'],
                'excluded'          => ['International airfare', 'Visa fees', 'Personal expenses', 'Tips'],
                'requirements'      => ['Valid passport', 'Travel insurance recommended'],
                'faqs'              => [['q' => 'Is this tour suitable for children?', 'a' => 'Yes, the tour is family-friendly and suitable for all ages.']],
                'featured'          => true,
                'active'            => true,
                'views'             => 0,
                'meta_title'        => 'Golden Triangle Cultural Tour — 7 Days',
                'meta_description'  => 'Explore Delhi, Agra, and Jaipur on a 7-day cultural tour from TrekBazar.',
            ]
        );
        $this->command->line('  [5/15] Package created/found: Test — Golden Triangle Cultural Tour');
    }

    // ── 6. ITINERARY DAYS ────────────────────────────────────────────────────
    private function seedItineraryDays(): void
    {
        $package = Package::where('slug', 'test-golden-triangle-cultural-tour')->first();
        if (!$package) { $this->command->warn('  [6/15] Package not found — skipping itinerary days.'); return; }

        $days = [
            [
                'package_id'       => $package->id,
                'day_number'       => 1,
                'title'            => 'Arrival in Delhi',
                'description'      => 'Arrive at Indira Gandhi International Airport. Transfer to hotel. Evening walk at Connaught Place.',
                'accommodation'    => 'Hotel in Delhi',
                'meals'            => ['dinner'],
                'distance_km'      => 20,
                'altitude_m'       => 216,
                'elevation_gain_m' => 0,
                'elevation_loss_m' => 0,
                'place_name'       => 'New Delhi',
                'notes'            => 'Wear comfortable walking shoes.',
            ],
            [
                'package_id'       => $package->id,
                'day_number'       => 2,
                'title'            => 'Old and New Delhi Tour',
                'description'      => 'Visit Qutb Minar, Red Fort, Jama Masjid, and India Gate. Street food walk in Chandni Chowk.',
                'accommodation'    => 'Hotel in Delhi',
                'meals'            => ['breakfast', 'lunch'],
                'distance_km'      => 45,
                'altitude_m'       => 216,
                'elevation_gain_m' => 0,
                'elevation_loss_m' => 0,
                'place_name'       => 'Delhi',
                'notes'            => 'Keep valuables secure in crowded markets.',
            ],
        ];

        foreach ($days as $day) {
            ItineraryDay::firstOrCreate(
                ['package_id' => $package->id, 'day_number' => $day['day_number']],
                $day
            );
        }
        $this->command->line('  [6/15] ItineraryDays created: 2 days for Golden Triangle package');
    }

    // ── 7. BOOKING ───────────────────────────────────────────────────────────
    private function seedBooking(): void
    {
        $package = Package::where('slug', 'test-golden-triangle-cultural-tour')->first();
        $user    = User::where('email', 'testuser@pathtosnow.com')->first();

        if (!$package) { $this->command->warn('  [7/15] Package not found — skipping booking.'); return; }

        $existing = Booking::where('customer_email', 'testuser@pathtosnow.com')
            ->where('package_id', $package->id)->first();

        if (!$existing) {
            Booking::create([
                'package_id'               => $package->id,
                'user_id'                  => $user?->id,
                'customer_name'            => 'Test Traveller',
                'customer_email'           => 'testuser@pathtosnow.com',
                'customer_phone'           => '+977-9800000001',
                'customer_nationality'     => 'Nepali',
                'travel_date'              => now()->addDays(30)->toDateString(),
                'group_size'               => 2,
                'special_requests'         => 'Vegetarian meals preferred.',
                'emergency_contact_name'   => 'Emergency Contact',
                'emergency_contact_phone'  => '+977-9800000002',
                'price_per_person'         => 45000.00,
                'total_price'              => 90000.00,
                'currency'                 => 'NPR',
                'status'                   => 'pending',
                'admin_notes'              => 'Test booking — seeded by AdminTestDataSeeder.',
            ]);
        }
        $this->command->line('  [7/15] Booking created/found for: testuser@pathtosnow.com');
    }

    // ── 8. POST ──────────────────────────────────────────────────────────────
    private function seedPost(): void
    {
        $admin   = User::where('role', 'admin')->first();
        $package = Package::where('slug', 'test-golden-triangle-cultural-tour')->first();

        Post::firstOrCreate(
            ['slug' => 'test-top-10-tips-for-visiting-the-taj-mahal'],
            [
                'user_id'            => $admin?->id ?? 1,
                'title'              => 'Test — Top 10 Tips for Visiting the Taj Mahal',
                'excerpt'            => 'Planning a visit to the Taj Mahal? These insider tips will make your experience unforgettable.',
                'content'            => '<h2>1. Go at Sunrise</h2><p>The Taj Mahal at sunrise is breathtaking. Crowds are thinner and the light is magical.</p><h2>2. Book Tickets Online</h2><p>Avoid long queues by booking your entry tickets online before visiting.</p>',
                'cover_image'        => null,
                'post_type'          => 'travel_guide',
                'category'           => 'Tips & Advice',
                'tags'               => ['taj mahal', 'india', 'travel tips', 'agra'],
                'published'          => true,
                'published_at'       => now(),
                'views'              => 0,
                'read_time'          => 5,
                'meta_title'         => 'Top 10 Tips for Visiting the Taj Mahal',
                'meta_description'   => 'Insider tips to make the most of your Taj Mahal visit — sunrise, tickets, and more.',
                'related_package_id' => $package?->id,
            ]
        );
        $this->command->line('  [8/15] Post created/found: Top 10 Tips for Visiting the Taj Mahal');
    }

    // ── 9. CATEGORY (for products) ───────────────────────────────────────────
    private function seedCategory(): void
    {
        Category::firstOrCreate(
            ['slug' => 'trekking-gear'],
            [
                'name'        => 'Trekking Gear',
                'slug'        => 'trekking-gear',
                'description' => 'Essential gear for trekkers and hikers.',
                'image'       => null,
                'sort_order'  => 1,
            ]
        );
        $this->command->line('  [9/15] Category created/found: Trekking Gear');
    }

    // ── 10. PRODUCT ──────────────────────────────────────────────────────────
    private function seedProduct(): void
    {
        $category = Category::where('slug', 'trekking-gear')->first();

        Product::firstOrCreate(
            ['slug' => 'test-trekking-poles-carbon-fiber'],
            [
                'category_id'   => $category?->id,
                'name'          => 'Test — Carbon Fibre Trekking Poles',
                'slug'          => 'test-trekking-poles-carbon-fiber',
                'description'   => '<p>Lightweight, collapsible carbon fibre trekking poles with anti-shock system. Suitable for all terrains.</p>',
                'price'         => 2999.00,
                'compare_price' => 3999.00,
                'images'        => [],
                'stock'         => 50,
                'sku'           => 'TRK-POLE-CF-001',
                'weight_grams'  => 480,
                'tags'          => ['trekking', 'poles', 'carbon fibre', 'hiking'],
                'specs'         => [
                    ['label' => 'Material', 'value' => 'Carbon Fibre'],
                    ['label' => 'Weight', 'value' => '480g per pair'],
                    ['label' => 'Length', 'value' => '65–135 cm adjustable'],
                ],
                'featured'      => true,
                'active'        => true,
                'meta_title'    => 'Carbon Fibre Trekking Poles — Lightweight Hiking Poles',
                'meta_description' => 'High-quality carbon fibre trekking poles for trekkers and hikers in Nepal.',
            ]
        );
        $this->command->line('  [10/15] Product created/found: Carbon Fibre Trekking Poles');
    }

    // ── 11. ORDER + ORDER ITEMS ──────────────────────────────────────────────
    private function seedOrder(): void
    {
        $user    = User::where('email', 'testuser@pathtosnow.com')->first();
        $product = Product::where('slug', 'test-trekking-poles-carbon-fiber')->first();

        $existing = Order::where('customer_email', 'testuser@pathtosnow.com')->first();
        if (!$existing && $product) {
            $order = Order::create([
                'user_id'              => $user?->id,
                'customer_name'        => 'Test Traveller',
                'customer_email'       => 'testuser@pathtosnow.com',
                'customer_phone'       => '+977-9800000001',
                'status'               => 'pending',
                'payment_status'       => 'unpaid',
                'payment_method'       => 'cash_on_delivery',
                'subtotal'             => 2999.00,
                'shipping_cost'        => 150.00,
                'total'                => 3149.00,
                'currency'             => 'NPR',
                'shipping_name'        => 'Test Traveller',
                'shipping_address'     => 'Thamel, Ward 26',
                'shipping_city'        => 'Kathmandu',
                'shipping_country'     => 'Nepal',
                'shipping_postal_code' => '44600',
                'notes'                => 'Test order — seeded by AdminTestDataSeeder.',
            ]);

            OrderItem::create([
                'order_id'      => $order->id,
                'product_id'    => $product->id,
                'product_name'  => $product->name,
                'product_image' => null,
                'quantity'      => 1,
                'unit_price'    => 2999.00,
                'total_price'   => 2999.00,
            ]);
        }
        $this->command->line('  [11/15] Order + OrderItem created/found for: testuser@pathtosnow.com');
    }

    // ── 12. CONTACT MESSAGE ──────────────────────────────────────────────────
    private function seedContactMessage(): void
    {
        $existing = ContactMessage::where('email', 'seed@pathtosnow.com')->first();
        if (!$existing) {
            ContactMessage::create([
                'name'       => 'Seed Test User',
                'email'      => 'seed@pathtosnow.com',
                'phone'      => '+977-9800000099',
                'subject'    => 'Enquiry about the Golden Triangle Tour',
                'message'    => 'Hello, I am interested in the Golden Triangle Cultural Tour. Could you please send me more details about the itinerary and pricing options for a group of 4?',
                'status'     => 'unread',
                'ip_address' => '127.0.0.1',
            ]);
        }
        $this->command->line('  [12/15] ContactMessage created/found for: seed@pathtosnow.com');
    }

    // ── 13. GALLERY ALBUM + GALLERY IMAGE ────────────────────────────────────
    private function seedGallery(): void
    {
        $album = GalleryAlbum::firstOrCreate(
            ['slug' => 'test-nepal-landscapes'],
            [
                'title'       => 'Test — Nepal Landscapes',
                'description' => 'A stunning collection of Nepal landscape photographs from our trekking expeditions.',
                'cover_image' => null,
                'is_active'   => true,
            ]
        );

        GalleryImage::firstOrCreate(
            ['gallery_album_id' => $album->id, 'sort_order' => 1],
            [
                'gallery_album_id' => $album->id,
                'image_path'       => '/images/placeholder-gallery.jpg',
                'caption'          => 'Annapurna Base Camp at dawn — 4,130m',
                'sort_order'       => 1,
            ]
        );
        $this->command->line('  [13/15] GalleryAlbum + GalleryImage created/found: Nepal Landscapes');
    }

    // ── 14. SLIDE ────────────────────────────────────────────────────────────
    private function seedSlide(): void
    {
        $existing = Slide::where('title', 'Test — Discover the Himalayas')->first();
        if (!$existing) {
            Slide::create([
                'image'      => null,
                'tag'        => 'New Destination',
                'title'      => 'Test — Discover the Himalayas',
                'desc'       => 'Journey through the world\'s highest peaks with TrekBazar — your trusted Himalayan travel partner.',
                'btn_text'   => 'Explore Tours',
                'btn_link'   => '/packages',
                'sort_order' => 99,
                'is_active'  => true,
            ]);
        }
        $this->command->line('  [14/15] Slide created/found: Discover the Himalayas');
    }

    // ── 15. STATIC PAGE ──────────────────────────────────────────────────────
    private function seedStaticPage(): void
    {
        $page = StaticPage::where('slug', 'privacy-policy')->first();
        if ($page) {
            $page->update([
                'meta_description' => 'Read TrekBazar\'s privacy policy — updated by AdminTestDataSeeder to verify admin page-edit functionality.',
            ]);
            $this->command->line('  [15/15] StaticPage updated: privacy-policy (meta_description refreshed)');
        } else {
            StaticPage::create([
                'slug'             => 'privacy-policy',
                'title'            => 'Privacy Policy',
                'content'          => '<h1>Privacy Policy</h1><p>We value your privacy. This page outlines how TrekBazar collects, uses, and protects your personal information.</p>',
                'meta_description' => 'TrekBazar privacy policy — how we handle your personal data.',
                'show_in_footer'   => true,
                'sort_order'       => 1,
            ]);
            $this->command->line('  [15/15] StaticPage created: privacy-policy');
        }
    }
}
