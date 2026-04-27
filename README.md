# PathToSnow — Nepal Travel & Gear Platform

![PathToSnow Logo](public/images/logo_footer.png)

PathToSnow is a curated travel marketplace and gear shop designed for adventurers exploring the Himalayas. Built by a local for the world, it connects travellers directly with expert guides and provides high-quality trekking equipment.

> **📌 Complete Setup Instructions Below** - Follow all steps to run this project successfully!

---

## 📋 Table of Contents

- [Features](#-key-features)
- [Tech Stack](#-technology-stack)
- [Requirements](#-requirements)
- [Installation](#-installation--setup)
- [Running Locally](#-running-locally)
- [Project Structure](#-project-structure)
- [Database](#-database-models)
- [Common Commands](#-common-commands)
- [Troubleshooting](#-troubleshooting)
- [Deployment](#-deployment)
- [Contributing](#-contributing)

---

## 🏔️ Key Features

- **Dynamic Booking System**: 6 specialized package types (Adventure, Trekking, Wildlife, etc.) with day-by-day itineraries.
- **Travel Packages Management**: Browse, filter, and book trekking expeditions with detailed information.
- **Gear Shop**: Fully functional e-commerce experience for Himalayan outdoor equipment with payment integration ready.
- **Travel Journal**: Expert-written blogs and guides on Nepali culture, food, and festivals.
- **Admin Dashboard**: Comprehensive management of packages, bookings, shop inventory, and blog posts.
- **Photo Gallery**: Stunning visual albums of Nepal's most beautiful destinations organized by location.
- **User Authentication**: Secure user registration, login, and profile management.
- **Contact Management**: Capture inquiries and feedback from visitors.
- **Static Pages**: Customizable content pages (About, Terms, Privacy, etc.).
- **Responsive Design**: Fully responsive UI that works on desktop, tablet, and mobile devices.

---

## 🛠️ Technology Stack

### Backend
- **PHP 8.2+** - Server-side programming language
- **Laravel 11** - Modern PHP web framework
- **Inertia.js** - Seamless Vue and Laravel integration
- **SQLite/MySQL** - Database (SQLite for development, MySQL for production)

### Frontend
- **Vue 3** - Progressive JavaScript framework
- **Inertia.js** - Frontend bridge to Laravel backend
- **Tailwind CSS** - Utility-first CSS framework
- **Vite** - Ultra-fast frontend build tool with hot module replacement

### Development Tools
- **Composer** - PHP package manager
- **npm/yarn** - JavaScript package manager
- **PHPUnit** - Testing framework
- **Laravel Sail** - Docker development environment (optional)

---

## 📋 Requirements

Before installation, ensure you have these tools installed on your system:

### Required:
- **PHP 8.2 or higher** - Check with: `php -v`
- **Composer 2.0+** - [Download here](https://getcomposer.org/download/)
- **Node.js 18+ and npm** - [Download here](https://nodejs.org/)
- **Git** - [Download here](https://git-scm.com/)

### Optional (for production):
- **MySQL 8.0+** - For production database
- **Docker** - For containerized deployment

### Verify Installation:
```bash
php -v
composer -v
node -v
npm -v
git -v
```

---

## 🚀 Installation & Setup

### Step 1: Clone the Repository

```bash
git clone https://github.com/kabu631/pathtosnow.git
cd pathtosnow
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

This installs all Laravel packages, Inertia.js, and other required PHP libraries.

### Step 3: Install JavaScript Dependencies

```bash
npm install
```

Or if you prefer yarn:
```bash
yarn install
```

### Step 4: Environment Configuration

Copy the example environment file:

```bash
cp .env.example .env
```

Edit `.env` and configure the following:

```env
APP_NAME="PathToSnow"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration (SQLite for Local Development)
DB_CONNECTION=sqlite

# If switching to MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=pathtosnow
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Mail Configuration (optional, use 'log' for testing)
MAIL_DRIVER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=1025

# Application Settings
APP_KEY=  # Will be generated in next step
APP_LOCALE=en
```

### Step 5: Generate Application Key

```bash
php artisan key:generate
```

This generates a unique encryption key for your application. You should see: `Application key set successfully.`

### Step 6: Setup Database

#### For SQLite (Recommended for Development):

```bash
# Create SQLite database file
touch database/database.sqlite
```

The file is created in `database/` directory automatically.

#### For MySQL (Production):

Make sure MySQL is running, then:
```bash
# Create database in MySQL first:
mysql -u root -p -e "CREATE DATABASE pathtosnow CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### Step 7: Run Database Migrations

```bash
php artisan migrate
```

This creates all database tables. You'll see output like:
```
Migrating: 2024_01_01_000001_create_users_table
Migrated:  2024_01_01_000001_create_users_table (in xxx ms)
...
```

### Step 8: Seed the Database (Optional but Recommended)

```bash
php artisan db:seed
```

This populates the database with sample data:
- Travel packages
- Package types (Adventure, Trekking, etc.)
- Blog post types
- Static pages
- Admin user account
- Sample products and bookings

**Skip this step if you want an empty database to start fresh.**

### Step 9: Create Storage Link

```bash
php artisan storage:link
```

This creates a symbolic link for file uploads and public storage.

### Step 10: Verify Installation

```bash
php artisan --version
```

Should output: `Laravel Framework 11.x.x`

---

## 🏃 Running Locally

**Important**: You need to run THREE commands in separate terminal windows:

### Terminal 1: Laravel Development Server

```bash
php artisan serve
```

Output will show:
```
Laravel development server started: http://127.0.0.1:8000
```

### Terminal 2: Vite Frontend Server (Hot Reload)

```bash
npm run dev
```

Output will show:
```
VITE v5.x.x ready in xxxms

➜  Local:   http://localhost:5173/
```

### Terminal 3: Optional - Run Tests

```bash
php artisan test
```

### Access the Application

- **Frontend**: `http://localhost:8000`
- **Admin Panel**: `/admin` (if you seeded the database)
- **Vite Dev Server**: `http://localhost:5173` (automatically used by Laravel)

---

## 🔐 Default Admin Credentials

After running seeders, use these credentials to access the admin panel:

- **URL**: `http://localhost:8000/admin`
- **Email**: `admin@pathtosnow.com`
- **Password**: `password`

⚠️ **IMPORTANT**: Change these credentials immediately in production!

---

## 📁 Project Structure

```
pathtosnow/
├── app/                        # Application code
│   ├── Http/
│   │   ├── Controllers/        # Route controllers
│   │   └── Middleware/         # Custom middleware
│   ├── Models/                 # Eloquent models
│   │   ├── Package.php         # Travel package model
│   │   ├── Booking.php         # Booking model
│   │   ├── Order.php           # Order model (e-commerce)
│   │   ├── Product.php         # Product model
│   │   ├── Post.php            # Blog post model
│   │   ├── GalleryAlbum.php    # Gallery model
│   │   ├── User.php            # User model
│   │   └── ...
│   └── Services/               # Business logic
│
├── database/                   # Database configuration
│   ├── migrations/             # Database schema migrations
│   │   ├── 2024_01_01_000001_create_users_table.php
│   │   ├── 2024_01_01_000002_create_packages_table.php
│   │   ├── 2024_01_01_000004_create_bookings_table.php
│   │   └── ...
│   ├── seeders/                # Database seeders (sample data)
│   │   ├── DatabaseSeeder.php
│   │   ├── StaticPageSeeder.php
│   │   └── TypesSeeder.php
│   └── database.sqlite         # SQLite database file
│
├── resources/                  # Frontend resources
│   ├── css/
│   │   └── app.css             # Global styles (Tailwind)
│   ├── js/
│   │   ├── app.js              # Vue app entry point
│   │   ├── Components/         # Vue components
│   │   │   ├── PackageCard.vue
│   │   │   ├── BookingForm.vue
│   │   │   └── ...
│   │   ├── Pages/              # Page components (Inertia)
│   │   │   ├── Home.vue
│   │   │   ├── Packages.vue
│   │   │   └── ...
│   │   └── Layouts/            # Layout components
│   │       ├── AppLayout.vue
│   │       └── AdminLayout.vue
│   └── views/                  # Blade templates
│       └── app.blade.php       # Main template
│
├── routes/                     # Route definitions
│   ├── web.php                 # Web routes
│   └── api.php                 # API routes (optional)
│
├── storage/                    # File uploads & logs
│   ├── app/
│   │   └── public/             # User uploads (linked to public/)
│   ├── framework/
│   └── logs/                   # Application logs
│
├── public/                     # Public assets (web root)
│   ├── images/                 # Public images
│   ├── storage/                # Symbolic link to storage/app/public
│   ├── build/                  # Compiled assets (auto-generated)
│   │   ├── manifest.json
│   │   └── assets/
│   └── index.php               # Application entry point
│
├── bootstrap/                  # Bootstrap files
│   └── app.php
│
├── config/                     # Configuration files
│   ├── app.php
│   ├── database.php
│   ├── mail.php
│   └── ...
│
├── vendor/                     # Composer dependencies
│   └── (all installed packages)
│
├── node_modules/               # npm dependencies
│   └── (all installed packages)
│
├── artisan                     # Artisan CLI entry point
├── composer.json               # PHP dependencies
├── package.json                # JavaScript dependencies
├── tailwind.config.js          # Tailwind configuration
├── vite.config.js              # Vite configuration
├── .env                        # Environment variables (create from .env.example)
├── .env.example                # Example environment file
└── README.md                   # This file
```

---

## 🗄️ Database Models

### Travel Management
- **Package** - Travel packages with details (price, duration, difficulty, etc.)
- **PackageType** - Types of packages (Trek, Adventure, Wildlife, etc.)
- **ItineraryDay** - Day-by-day itinerary for each package
- **Booking** - Customer bookings with status tracking
- **User** - User profiles and authentication

### E-Commerce
- **Product** - Gear shop products
- **Order** - Customer orders
- **OrderItem** - Items within orders

### Content Management
- **Post** - Blog posts and articles
- **PostType** - Blog post categories
- **GalleryAlbum** - Photo albums
- **GalleryImage** - Individual gallery photos
- **Slide** - Website slider images
- **StaticPage** - Customizable pages (About, Terms, etc.)

### Communication
- **ContactMessage** - Contact form submissions

---

## 🔌 Key Routes

Check `routes/web.php` for all routes. Key endpoints:

```
GET  /                    - Home page
GET  /packages            - Browse all packages
GET  /packages/{id}       - View package details
POST /bookings            - Create a booking
GET  /shop                - E-commerce shop
GET  /shop/{id}           - View product details
GET  /blog                - Blog/journal posts
GET  /gallery             - Photo gallery
GET  /admin               - Admin dashboard (protected)
```

---

## 📝 Common Commands

### Database
```bash
# Run migrations
php artisan migrate

# Roll back migrations
php artisan migrate:rollback

# Reset and re-seed database
php artisan migrate:refresh --seed

# Seed database with sample data
php artisan db:seed

# Create migration
php artisan make:migration create_table_name
```

### Code Generation
```bash
# Create a new model with migration
php artisan make:model ModelName -m

# Create a new controller
php artisan make:controller ControllerName

# Create a model, controller, and migration together
php artisan make:model ModelName -mcr
```

### Cache & Configuration
```bash
# Clear all cache
php artisan cache:clear

# Clear config cache
php artisan config:clear

# Cache configuration (production)
php artisan config:cache

# Cache routes (production)
php artisan route:cache
```

### Development
```bash
# Open Laravel Tinker (interactive shell)
php artisan tinker

# View all routes
php artisan route:list

# Run tests
php artisan test

# View current environment
php artisan env
```

### Maintenance
```bash
# Enable maintenance mode
php artisan down

# Disable maintenance mode
php artisan up

# Optimize application
php artisan optimize
```

---

## 🎨 Frontend Development

### Vue 3 Components
- Located in `resources/js/Components/`
- Use Composition API for modern Vue patterns
- Import and register in parent components

### Tailwind CSS
- Configuration: `tailwind.config.js`
- Main styles: `resources/css/app.css`
- Utility-first approach for styling

### Hot Module Replacement
When running `npm run dev`:
- Vue component changes appear instantly
- CSS changes apply without page reload
- JavaScript changes reload the page

### Build for Production
```bash
npm run build
```

Generates optimized assets in `public/build/`

---

## 🐛 Troubleshooting

### Issue: "No application encryption key has been specified"
```bash
php artisan key:generate
```

### Issue: Database connection error
**Solution**:
1. Verify `.env` database settings
2. Check if MySQL/SQLite is accessible
3. For SQLite: `touch database/database.sqlite`

```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

### Issue: Vite dev server not found
**Solution**:
- Run `npm run dev` in separate terminal
- Clear browser cache (Ctrl+Shift+Del)
- Restart both Laravel and Vite servers

### Issue: "Class not found" error
**Solution**:
```bash
composer dump-autoload
```

### Issue: Permission denied errors
**Solution** (Linux/Mac):
```bash
chmod -R 775 storage bootstrap/cache
chmod -R 775 database
```

### Issue: npm install fails
**Solution**:
```bash
rm -rf node_modules package-lock.json
npm cache clean --force
npm install
```

### Issue: Migrations fail
**Solution**:
```bash
# Check migration status
php artisan migrate:status

# Refresh and seed
php artisan migrate:refresh --seed
```

### Issue: Assets not loading (CSS/JS)
**Solution**:
```bash
# Rebuild frontend
npm run dev

# Or for production build
npm run build

# Clear Laravel cache
php artisan cache:clear
```

### Common Error Messages:

| Error | Solution |
|-------|----------|
| `SQLSTATE[HY000]: General error` | Ensure database file exists: `touch database/database.sqlite` |
| `Port 8000 already in use` | Use different port: `php artisan serve --port=8001` |
| `Node modules not installed` | Run `npm install` |
| `Vite not compiling` | Kill and restart `npm run dev` |
| `Migrations pending` | Run `php artisan migrate` |

---

## 📦 Deployment

### Build for Production

```bash
# Compile frontend assets
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Environment Variables

Update `.env` for production:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

### Web Server Configuration

#### Nginx (Recommended)
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/pathtosnow/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

#### Apache
Ensure `.htaccess` is properly configured in `public/` directory.

### Database Migration on Production

```bash
# Backup database first!
php artisan migrate --force
```

### Recommended Hosting
- **Shared Hosting**: Laravel Forge, A2Hosting
- **VPS**: DigitalOcean, Linode, Vultr
- **Cloud**: AWS, Google Cloud, Azure
- **Docker**: Deploy with Docker Compose

---

## 📚 Documentation & Resources

- [Laravel Documentation](https://laravel.com/docs/11.x/)
- [Vue 3 Documentation](https://vuejs.org/guide/introduction.html)
- [Inertia.js Documentation](https://inertiajs.com/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Vite Documentation](https://vitejs.dev/)

---

## 🤝 Contributing

We welcome contributions! Here's how:

1. **Fork** the repository
2. **Create** a feature branch: `git checkout -b feature/amazing-feature`
3. **Commit** your changes: `git commit -m 'Add amazing feature'`
4. **Push** to the branch: `git push origin feature/amazing-feature`
5. **Open** a Pull Request

Please follow these guidelines:
- Write clear commit messages
- Test your changes locally
- Update documentation if needed
- Follow Laravel & Vue 3 best practices

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👨‍💻 Author

**Kabu** - [GitHub Profile](https://github.com/kabu631)

---

## 📞 Support & Contact

For issues, feature requests, or questions:
- Open an issue on [GitHub](https://github.com/kabu631/pathtosnow/issues)
- Review existing documentation above

---

## 🏔️ Special Thanks

Built with ❤️ in Kathmandu for Nepal's adventure community.

**Last Updated**: April 2026
**Version**: 1.0.0
