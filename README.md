# PathToSnow — Nepal Travel & Gear Platform

![PathToSnow Logo](public/images/logo_footer.png)

PathToSnow is a curated travel marketplace and gear shop designed for adventurers exploring the Himalayas. Built by a local for the world, it connects travellers directly with expert guides and provides high-quality trekking equipment.

> **📌 Complete Setup Instructions Below** — Follow all steps to run this project successfully!

---

## 📋 Table of Contents

- [Features](#-key-features)
- [Tech Stack](#-technology-stack)
- [Requirements](#-requirements)
- [Installation](#-installation--setup)
- [Running Locally](#-running-locally)
- [Project Structure](#-project-structure)
- [Database](#-database-models)
- [Email Services](#-email-services)
- [Admin Panel](#-admin-panel)
- [Common Commands](#-common-commands)
- [Troubleshooting](#-troubleshooting)
- [Deployment](#-deployment)
- [Contributing](#-contributing)

---

## 🏔️ Key Features

### Public
- **Dynamic Booking System** — 6 specialised package types (Adventure, Trekking, Wildlife, etc.) with day-by-day itineraries.
- **Travel Packages** — Browse, filter, and book trekking expeditions with detailed information.
- **Gear Shop** — Fully functional e-commerce experience for Himalayan outdoor equipment.
- **Travel Journal** — Expert-written blogs and guides on Nepali culture, food, and festivals.
- **Photo Gallery** — Stunning visual albums of Nepal's most beautiful destinations.
- **Contact Form** — Visitor enquiry capture with admin reply-by-email.

### Authentication
- **User Registration & Login** — Secure session-based authentication.
- **Forgot Password** — Full email-based password reset flow (custom pages, PHPMailer delivery).
- **Profile Management** — Users can view and update their account details.

### Admin Panel
- **Dashboard** — Overview of bookings, orders, messages, and users.
- **Full CRUD** — Manage packages, package types, posts, post types, products, gallery albums, sliders, and static pages.
- **User Management** — Search, filter, edit roles/passwords, and delete users. Admins cannot delete their own account.
- **Booking Management** — Update booking status, save internal notes, and delete bookings.
- **Order Management** — Update order and payment status, view full order details, and delete orders.
- **Contact Messages** — View, reply by email, and delete contact messages directly from the panel.
- **File Upload** — Drag-and-drop image upload for hero banners (Package Types & Post Types).

### UX
- **Page Loading Overlay** — Smooth spinner shown on slow Inertia navigations (150 ms delay to avoid flashing on fast loads).
- **Responsive Design** — Fully responsive UI across desktop, tablet, and mobile.

---

## 🛠️ Technology Stack

### Backend
| Package | Purpose |
|---|---|
| PHP 8.2+ | Server-side language |
| Laravel 11 | Web framework |
| Inertia.js (Laravel adapter) | Server-driven SPA bridge |
| PHPMailer 7 | Transactional email via SMTP |
| SQLite / MySQL | Database |

### Frontend
| Package | Purpose |
|---|---|
| Vue 3 (Composition API) | UI framework |
| Inertia.js (Vue 3 adapter) | Frontend SPA bridge |
| Tailwind CSS 3 | Utility-first styling |
| Vite | Build tool & hot reload |

---

## 📋 Requirements

### Required
- **PHP 8.2+** — `php -v`
- **Composer 2.0+** — [Download](https://getcomposer.org/download/)
- **Node.js 18+ & npm** — [Download](https://nodejs.org/)
- **Git** — [Download](https://git-scm.com/)

### Optional (production)
- **MySQL 8.0+** — For production database
- **SMTP credentials** — Gmail App Password, Mailgun, etc. for email delivery

---

## 🚀 Installation & Setup

### Step 1 — Clone

```bash
git clone https://github.com/kabu631/pathtosnow.git
cd pathtosnow
```

### Step 2 — Install PHP dependencies

```bash
composer install
```

### Step 3 — Install JavaScript dependencies

```bash
npm install
```

### Step 4 — Environment configuration

```bash
cp .env.example .env
```

Edit `.env`:

```env
APP_NAME="PathToSnow"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database — SQLite for local dev
DB_CONNECTION=sqlite

# For MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=pathtosnow
# DB_USERNAME=root
# DB_PASSWORD=your_password

# ── Email (PHPMailer reads these) ──────────────────────────────
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your-app-password   # Gmail: use an App Password, not your login password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hello@pathtosnow.com
MAIL_FROM_NAME="PathToSnow Nepal"
```

> **Gmail tip** — Enable 2-Step Verification, then create an App Password at [myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords). Paste that 16-character code as `MAIL_PASSWORD`.

### Step 5 — Generate app key

```bash
php artisan key:generate
```

### Step 6 — Create SQLite database

```bash
# Windows
type nul > database/database.sqlite

# Mac / Linux
touch database/database.sqlite
```

_(Skip if using MySQL — create the database in MySQL first.)_

### Step 7 — Run migrations

```bash
php artisan migrate
```

### Step 8 — Seed sample data _(optional but recommended)_

```bash
php artisan db:seed
```

Inserts sample packages, post types, an admin account, and demo products.

### Step 9 — Create storage link

```bash
php artisan storage:link
```

---

## 🏃 Running Locally

Run these **two commands in separate terminals**:

```bash
# Terminal 1 — Laravel
php artisan serve

# Terminal 2 — Vite (hot reload)
npm run dev
```

Then open **http://localhost:8000**.

---

## 🔐 Default Admin Credentials

After seeding:

| Field | Value |
|---|---|
| URL | http://localhost:8000/admin |
| Email | admin@himalayatrails.com |
| Password | password |

⚠️ **Change these immediately in any non-local environment.**

---

## 📁 Project Structure

```
pathtosnow/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # Admin panel controllers
│   │   │   │   ├── AdminBookingController.php
│   │   │   │   ├── AdminContactController.php
│   │   │   │   ├── AdminOrderController.php
│   │   │   │   ├── AdminUserController.php
│   │   │   │   └── ...
│   │   │   ├── Auth/
│   │   │   │   └── AuthController.php   # Login, register, forgot/reset password
│   │   │   └── Public/             # Public-facing controllers
│   │   └── Middleware/
│   ├── Models/                     # Eloquent models
│   │   ├── User.php
│   │   ├── Booking.php
│   │   ├── Order.php
│   │   ├── Package.php
│   │   └── ...
│   └── Services/
│       └── MailService.php         # PHPMailer wrapper for all transactional emails
│
├── resources/
│   ├── css/
│   │   └── app.css                 # Global styles + page-loader overlay
│   └── js/
│       ├── app.js                  # Vue entry point + Inertia loading overlay
│       ├── Layouts/
│       │   ├── AppLayout.vue
│       │   └── AdminLayout.vue
│       └── Pages/
│           ├── Auth/
│           │   ├── Login.vue
│           │   ├── ForgotPassword.vue  # Password reset request page
│           │   └── ResetPassword.vue   # New password form
│           └── Admin/
│               ├── Users/
│               │   ├── Index.vue       # User list with search & role filter
│               │   └── Edit.vue        # Edit profile + change password
│               ├── Bookings/
│               │   └── Show.vue
│               ├── Orders/
│               │   └── Show.vue
│               ├── Contact/
│               │   └── Show.vue
│               ├── PackageTypes/
│               │   └── Form.vue        # Drag-and-drop hero image upload
│               └── PostTypes/
│                   └── Form.vue        # Drag-and-drop hero image upload
│
├── routes/
│   └── web.php
├── composer.json
├── package.json
├── tailwind.config.js
├── vite.config.js
└── .env.example
```

---

## 🗄️ Database Models

### Travel
| Model | Description |
|---|---|
| Package | Travel packages (price, duration, difficulty) |
| PackageType | Trek, Adventure, Wildlife, etc. |
| ItineraryDay | Day-by-day itinerary per package |
| Booking | Customer bookings with status tracking |

### E-Commerce
| Model | Description |
|---|---|
| Product | Gear shop items |
| Order | Customer orders |
| OrderItem | Line items within an order |

### Content
| Model | Description |
|---|---|
| Post | Blog posts and articles |
| PostType | Blog categories |
| GalleryAlbum | Photo albums |
| GalleryImage | Individual gallery photos |
| Slide | Homepage slider images |
| StaticPage | About, Terms, Privacy, etc. |

### Communication
| Model | Description |
|---|---|
| ContactMessage | Contact form submissions |
| User | Authentication + role (user / admin) |

---

## 📧 Email Services

All transactional emails are sent via **PHPMailer** through the `App\Services\MailService` class. Configure SMTP in `.env` (see Step 4).

| Trigger | Email sent |
|---|---|
| User registers | Welcome email |
| Forgot password | Password reset link |
| Booking submitted | Booking confirmation to customer |
| Booking status changed | Status update notification |
| Order placed | Order confirmation to customer |
| Admin replies to contact message | Reply email to visitor |

> If SMTP is not configured, email failures are logged to `storage/logs/laravel.log` and the application continues to work.

---

## 🛡️ Admin Panel

Access at `/admin` (requires `role = admin`).

### User management rules
- Admins can **edit** any user, including themselves (profile, email, role, password).
- Admins **cannot delete** their own account — the Delete button is hidden for the currently logged-in user.

### Image uploads
Hero images for Package Types and Post Types can be uploaded by drag-and-drop or file picker. Images are stored in `storage/app/public/uploads/` and served via the `/storage` symlink.

### Contact replies
Opening a contact message and clicking **Write a Reply** sends an email directly to the visitor via PHPMailer and automatically marks the message as `replied`.

---

## 🔌 Key Routes

```
GET  /                              Home
GET  /packages                      Browse packages
GET  /packages/{id}                 Package detail
POST /bookings                      Create booking
GET  /shop                          Gear shop
GET  /blog                          Blog
GET  /gallery                       Photo gallery
GET  /forgot-password               Forgot password form
POST /forgot-password               Send reset link
GET  /reset-password/{token}        Reset password form
POST /reset-password                Save new password

GET  /admin                         Admin dashboard
GET  /admin/users                   User list
GET  /admin/users/{id}/edit         Edit user
PUT  /admin/users/{id}              Update user
DEL  /admin/users/{id}              Delete user
GET  /admin/bookings/{id}           Booking detail
DEL  /admin/bookings/{id}           Delete booking
GET  /admin/orders/{id}             Order detail
DEL  /admin/orders/{id}             Delete order
POST /admin/contact/{id}/reply      Reply to contact message
DEL  /admin/contact/{id}            Delete message
```

---

## 📝 Common Commands

```bash
# Database
php artisan migrate
php artisan migrate:rollback
php artisan migrate:refresh --seed
php artisan db:seed

# Cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Code generation
php artisan make:model ModelName -m
php artisan make:controller ControllerName

# Production optimisation
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build

# Utilities
php artisan route:list
php artisan tinker
php artisan env
```

---

## 🐛 Troubleshooting

| Problem | Fix |
|---|---|
| `No application encryption key` | `php artisan key:generate` |
| SQLite not found | `touch database/database.sqlite` (Mac/Linux) or `type nul > database/database.sqlite` (Windows) |
| Vite dev server not found | Run `npm run dev` in a separate terminal |
| `Class not found` | `composer dump-autoload` |
| Emails not sending | Check `.env` SMTP settings; errors go to `storage/logs/laravel.log` |
| Permission denied (Linux/Mac) | `chmod -R 775 storage bootstrap/cache` |
| npm install fails | `rm -rf node_modules && npm cache clean --force && npm install` |
| Port 8000 in use | `php artisan serve --port=8001` |

---

## 📦 Deployment

```bash
# 1 — Build frontend
npm run build

# 2 — Optimise Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3 — Run migrations
php artisan migrate --force
```

Update `.env` for production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

### Nginx config

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
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

---

## 📚 Resources

- [Laravel 11 Docs](https://laravel.com/docs/11.x/)
- [Vue 3 Docs](https://vuejs.org/)
- [Inertia.js Docs](https://inertiajs.com/)
- [PHPMailer Docs](https://github.com/PHPMailer/PHPMailer)
- [Tailwind CSS Docs](https://tailwindcss.com/docs)

---

## 🤝 Contributing

1. Fork the repository
2. Create a branch: `git checkout -b feature/my-feature`
3. Commit: `git commit -m 'Add my feature'`
4. Push: `git push origin feature/my-feature`
5. Open a Pull Request

---

## 📄 License

MIT — see the [LICENSE](LICENSE) file.

---

## 👨‍💻 Author

**Kabu** — [github.com/kabu631](https://github.com/kabu631)

Built with ❤️ in Kathmandu for Nepal's adventure community.

**Last updated**: April 2026 | **Version**: 1.1.0
