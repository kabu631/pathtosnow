<?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\{HomeController, PackageController, BlogController, ShopController, CartController, BookingController, LocationController, StaticPageController, ContactController};
use App\Http\Controllers\Admin\{DashboardController, AdminPackageController, ItineraryController, AdminBookingController, AdminPostController, AdminProductController, AdminOrderController, AdminLocationController, LocationImageController, AdminTestimonialController, AdminSlideController, AdminPageController, AdminContactController, AdminPackageTypeController, AdminPostTypeController, AdminUserController};
use App\Http\Controllers\Auth\AuthController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Packages (all 6 types)
Route::prefix('packages')->name('packages.')->group(function () {
    Route::get('/', [PackageController::class, 'index'])->name('index');
    Route::get('/adventure', [PackageController::class, 'adventure'])->name('adventure');
    Route::get('/trekking', [PackageController::class, 'trekking'])->name('trekking');
    Route::get('/valley-visits', [PackageController::class, 'valleyVisit'])->name('valley_visit');
    Route::get('/national-parks', [PackageController::class, 'nationalParks'])->name('national_park');
    Route::get('/wildlife', [PackageController::class, 'wildlife'])->name('wildlife_reserve');
    Route::get('/lakes', [PackageController::class, 'lakes'])->name('lake');
    Route::redirect('/type/wildlife', '/packages/wildlife', 301);
    Route::redirect('/type/wildlife-reserve', '/packages/wildlife', 301);
    Route::redirect('/type/wildlife-reserves', '/packages/wildlife', 301);
    Route::get('/type/{slug}', [PackageController::class, 'dynamicCategory'])->name('type_category');
    Route::get('/{slug}', [PackageController::class, 'show'])->name('show');
});

// Abroad
Route::get('/abroad/{slug}', [\App\Http\Controllers\Public\AbroadController::class, 'show'])->name('abroad.show');

// Bookings
Route::prefix('book')->name('bookings.')->group(function () {
    Route::get('/success/{reference}', [BookingController::class, 'success'])->name('success');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->middleware('auth')->name('my');
    Route::post('/custom-quote', [BookingController::class, 'storeCustomQuote'])->middleware('throttle:5,1')->name('store_custom_quote');
    Route::get('/{package:slug}', [BookingController::class, 'create'])->name('create');
    Route::post('/{package:slug}', [BookingController::class, 'store'])->middleware('throttle:5,1')->name('store');
});

// Travel Guide & Blog
Route::prefix('travel-guide')->name('guide.')->group(function () {
    Route::get('/', [BlogController::class, 'guideIndex'])->name('index');
    Route::get('/type/{slug}', [BlogController::class, 'dynamicCategory'])->name('type_category');
    Route::get('/food', [BlogController::class, 'food'])->name('food');
    Route::get('/culture', [BlogController::class, 'culture'])->name('culture');
    Route::get('/festivals', [BlogController::class, 'festivals'])->name('festivals');
    Route::get('/city-tours', [BlogController::class, 'cityTours'])->name('city_tours');
});

Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

// Static pages (Privacy Policy, Terms)
Route::get('/pages/{slug}', [StaticPageController::class, 'show'])->name('pages.show');

// Dedicated pages
Route::get('/about', fn() => inertia('Public/About'))->name('about');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:6,1')->name('contact.store');

// Top Locations
Route::prefix('locations')->name('locations.')->group(function () {
    Route::get('/', [LocationController::class, 'index'])->name('index');
    Route::get('/{slug}', [LocationController::class, 'show'])->name('show');
});

// Gear Shop
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('index');
    Route::get('/{slug}', [ShopController::class, 'show'])->name('show');
});

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/update', [CartController::class, 'update'])->name('update');
    Route::delete('/remove', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::get('/success', [CartController::class, 'success'])->name('success');
});

// ── AUTH ───────────────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1')->name('password.update');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ── ADMIN ROUTES ───────────────────────────────────────────────────────────

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('packages')->name('packages.')->group(function () {
        Route::get('/', [AdminPackageController::class, 'index'])->name('index');
        Route::get('/create', [AdminPackageController::class, 'create'])->name('create');
        Route::post('/', [AdminPackageController::class, 'store'])->name('store');
        Route::get('/{package}/edit', [AdminPackageController::class, 'edit'])->name('edit');
        Route::put('/{package}', [AdminPackageController::class, 'update'])->name('update');
        Route::delete('/{package}', [AdminPackageController::class, 'destroy'])->name('destroy');
        Route::patch('/{package}/toggle', [AdminPackageController::class, 'toggle'])->name('toggle');

        // Itinerary days (nested)
        Route::get('/{package}/itinerary', [ItineraryController::class, 'index'])->name('itinerary');
        Route::post('/{package}/itinerary', [ItineraryController::class, 'store'])->name('itinerary.store');
        Route::put('/{package}/itinerary/{day}', [ItineraryController::class, 'update'])->name('itinerary.update');
        Route::delete('/{package}/itinerary/{day}', [ItineraryController::class, 'destroy'])->name('itinerary.destroy');
        Route::post('/{package}/itinerary/reorder', [ItineraryController::class, 'reorder'])->name('itinerary.reorder');
    });

    Route::resource('package-types', AdminPackageTypeController::class)->except(['show']);
    Route::resource('countries', \App\Http\Controllers\Admin\AdminCountryController::class)->except(['show']);

    // Bookings
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [AdminBookingController::class, 'index'])->name('index');
        Route::get('/{booking}', [AdminBookingController::class, 'show'])->name('show');
        Route::patch('/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('status');
        Route::patch('/{booking}/notes', [AdminBookingController::class, 'updateNotes'])->name('notes');
        Route::delete('/{booking}', [AdminBookingController::class, 'destroy'])->name('destroy');
    });

    // Posts
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [AdminPostController::class, 'index'])->name('index');
        Route::get('/create', [AdminPostController::class, 'create'])->name('create');
        Route::post('/', [AdminPostController::class, 'store'])->name('store');
        Route::get('/{post}/edit', [AdminPostController::class, 'edit'])->name('edit');
        Route::put('/{post}', [AdminPostController::class, 'update'])->name('update');
        Route::delete('/{post}', [AdminPostController::class, 'destroy'])->name('destroy');
        Route::patch('/{post}/publish', [AdminPostController::class, 'togglePublish'])->name('publish');
    });

    Route::resource('post-types', AdminPostTypeController::class)->except(['show']);

    // Products
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('index');
        Route::get('/create', [AdminProductController::class, 'create'])->name('create');
        Route::post('/', [AdminProductController::class, 'store'])->name('store');
        Route::get('/{product}/edit', [AdminProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [AdminProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [AdminProductController::class, 'destroy'])->name('destroy');
    });

    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{order}', [AdminOrderController::class, 'show'])->name('show');
        Route::patch('/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('status');
        Route::patch('/{order}/payment', [AdminOrderController::class, 'updatePayment'])->name('payment');
        Route::delete('/{order}', [AdminOrderController::class, 'destroy'])->name('destroy');
    });

    // Static Pages (About Us, Privacy Policy, etc.)
    Route::prefix('pages')->name('pages.')->group(function () {
        Route::get('/', [AdminPageController::class, 'index'])->name('index');
        Route::get('/{page}/edit', [AdminPageController::class, 'edit'])->name('edit');
        Route::patch('/{page}', [AdminPageController::class, 'update'])->name('update');
    });

    // Contact Messages
    Route::prefix('contact')->name('contact.')->group(function () {
        Route::get('/', [AdminContactController::class, 'index'])->name('index');
        Route::get('/{message}', [AdminContactController::class, 'show'])->name('show');
        Route::patch('/{message}/replied', [AdminContactController::class, 'markReplied'])->name('replied');
        Route::post('/{message}/reply', [AdminContactController::class, 'sendReply'])->name('reply');
        Route::delete('/{message}', [AdminContactController::class, 'destroy'])->name('destroy');
    });

    // Locations (Regions)
    Route::prefix('locations')->name('locations.')->group(function () {
        Route::get('/', [AdminLocationController::class, 'index'])->name('index');
        Route::get('/create', [AdminLocationController::class, 'create'])->name('create');
        Route::post('/', [AdminLocationController::class, 'store'])->name('store');
        Route::get('/{location}', [AdminLocationController::class, 'show'])->name('show');
        Route::get('/{location}/edit', [AdminLocationController::class, 'edit'])->name('edit');
        Route::put('/{location}', [AdminLocationController::class, 'update'])->name('update');
        Route::delete('/{location}', [AdminLocationController::class, 'destroy'])->name('destroy');
        
        // Location Images
        Route::post('/{location}/images', [LocationImageController::class, 'store'])->name('images.store');
        Route::delete('/images/{image}', [LocationImageController::class, 'destroy'])->name('images.destroy');
    });

    // Testimonials
    Route::patch('/testimonials/{testimonial}/toggle', [AdminTestimonialController::class, 'toggle'])->name('testimonials.toggle');
    Route::resource('testimonials', AdminTestimonialController::class)->except(['show']);

    // Slides
    Route::post('slides/reorder', [AdminSlideController::class, 'reorder'])->name('slides.reorder');
    Route::resource('slides', AdminSlideController::class);

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Shared image upload
    Route::post('/upload', function (\Illuminate\Http\Request $req) {
        $req->validate(['image' => 'required|image|max:5120']);
        $url = \App\Services\ImageService::store($req->file('image'), 'general');
        return response()->json(['url' => asset(ltrim($url, '/'))]);
    })->name('upload');
});
