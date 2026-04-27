# TrekBazar — Nepal Travel Platform
## Stack: Laravel 11 + Inertia.js + Vue 3 + SQLite + Tailwind CSS

---

## Features
- 6 package types: Adventure, Valley Visit, Trekking, National Parks, Wildlife Reserves, Lakes
- Day-by-day itinerary builder per package
- Visitor booking system with admin panel management
- Blog & Travel Guide (food, culture, city tour, festivals, general)
- Trekking gear ecommerce shop with cart + checkout
- Related suggestions on packages and posts
- Full admin panel: packages, itineraries, bookings, posts, products, orders

---

## Setup (step by step)

### 1. Create Laravel project
```bash
composer create-project laravel/laravel trekbazar
cd trekbazar
```

### 2. Install dependencies
```bash
# PHP
composer require inertiajs/inertia-laravel

# JS
npm install @inertiajs/vue3 vue @vitejs/plugin-vue
npm install -D tailwindcss postcss autoprefixer @tailwindcss/typography @tailwindcss/forms
npx tailwindcss init -p
```

### 3. Copy all project files into the Laravel folder

### 4. Configure SQLite
```bash
# Create the database file
touch database/database.sqlite

# .env — SQLite config (already set in .env.example)
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/your/trekbazar/database/database.sqlite
```

### 5. Run migrations and seed
```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

### 6. Run the app
```bash
php artisan serve   # terminal 1 → http://localhost:8000
npm run dev         # terminal 2
```

### 7. Make yourself admin
```bash
php artisan tinker
User::where('email','admin@trekbazar.com')->update(['role'=>'admin']);
```

---

## Default credentials (seeded)
- Admin: admin@trekbazar.com / password
- Customer: customer@trekbazar.com / password

---

## Package types
| Type | Slug | Examples |
|------|------|---------|
| Adventure | adventure | Bungee, Rafting, Kayaking, Paragliding |
| Valley Visit | valley_visit | Pashupatinath, Boudhanath, Patan Durbar |
| Trekking | trekking | Annapurna, Langtang, Manaslu, EBC |
| National Park | national_park | Chitwan, Sagarmatha, Langtang NP |
| Wildlife Reserve | wildlife_reserve | Bardia, Koshi Tappu, Parsa |
| Lake | lake | Rara, Shey Phoksundo, Fewa, Khaptad |

---

## Blog / Travel Guide post types
- `blog` — General travel tips
- `food` — Nepali cuisine, restaurants, street food
- `culture` — Traditions, customs, etiquette
- `festival` — Dashain, Tihar, Holi, Indra Jatra
- `city_tour` — Kathmandu, Pokhara, Chitwan guides
- `travel_guide` — Visa, weather, packing, transport
