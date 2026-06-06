# Click & Fix — Backend

Car repair & roadside assistance mobile app backend built with **Laravel 12**, **Filament Admin**, **Sanctum Auth**, and **FCM Push**.

## Overview

Click & Fix connects drivers with roadside assistance services. Users can request help (emergency, battery, tire, fuel, etc.), track orders in real-time, and receive push notifications. Admins manage the full workflow via a Filament dashboard.

### Mobile App (Flutter)
- **iOS/Android** — user registration, car management, order placement, push notifications
- **Guest flow** — browse without signing up; prompt login at checkout

## Features

### User App (API)
- Registration / Login / Guest Login (Sanctum tokens)
- Email verification & password reset
- Profile management + locale preference (en/ar)
- Car management (VIN, license, make, model, year)
- Order placement & tracking (emergency, service, part)
- Push notifications via FCM (bilingual: English / Arabic)
- Technician assignment
- Report issue flow

### Admin Panel (Filament)
- **Users** — manage, filter, search
- **Orders** — full lifecycle CRUD, status tracking
- **Cars** — manage user vehicles
- **Services** & **Service Categories**
- **Parts** & **Brand Categories**
- **Invoices** & **Quotations** — auto‑generated PDFs (Arabic/English)
- **Notifications** — send, approve, cancel
- **Issues** / **Reports** — support & inspection
- **Lookup tables** — Car Types, Engine Types, Battery Voltages, Emergencies, Brands
- **Role‑based access** via Filament Shield + Spatie Permissions
- **Bilingual fields** (en/ar) on all content entities

### Notifications
- **FCM** push via `app/Services/FCMService.php`
- Device tokens stored in `device_tokens` table, cleaned on each login
- Bilingual titles/bodies based on user locale
- Notifications logged in `notifications` table

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | [Laravel 12](https://laravel.com) |
| Admin Panel | [Filament 3](https://filamentphp.com) |
| API Auth | [Laravel Sanctum](https://laravel.com/docs/sanctum) |
| Permissions | [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) + [Filament Shield](https://github.com/bezhanSalleh/filament-shield) |
| Push Notifications | Firebase Cloud Messaging (FCM) |
| PDF Generation | [Barryvdh Laravel DomPDF](https://github.com/barryvdh/laravel-dompdf) |
| Arabic Support | [ar-php](https://github.com/khaled-alshamaa/ar-php) |
| Mail (Dev) | Mailtrap SMTP |
| Mail (Prod) | Resend |
| Database | MySQL |
| Queue | Database / Redis |

## Requirements

- PHP 8.2+
- Composer 2
- MySQL 8+ / MariaDB 10+
- Node.js & NPM (for Filament assets)

## Installation

```bash
# 1. Clone the repository
git clone <repo-url> clickandfix-backend
cd clickandfix-backend

# 2. Install PHP dependencies
composer install

# 3. Environment configuration
cp .env.example .env
php artisan key:generate

# 4. Configure .env (database, mail, FCM, etc.)
#    See .env.example for reference

# 5. Run migrations & seeders
php artisan migrate --seed

# 6. Create Filament Shield permissions
php artisan shield:generate

# 7. Install & build frontend assets
npm install
npm run build

# 8. Start dev server
php artisan serve
```

## Environment Variables

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=carandfixbackend
DB_USERNAME=root
DB_PASSWORD=

# Mail (Development — Mailtrap)
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@clickandfixqa.com"
MAIL_FROM_NAME="${APP_NAME}"

# Mail (Production — Resend)
# MAIL_MAILER=resend
# RESEND_API_KEY=re_xxxxx

# Firebase Cloud Messaging
FIREBASE_PROJECT_ID=click-and-fix-xxxxx
FIREBASE_CLIENT_EMAIL=firebase-adminsdk-xxxxx@click-and-fix-xxxxx.iam.gserviceaccount.com
FIREBASE_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----"

# Queue
QUEUE_CONNECTION=database  # or redis

# Cache & Session
CACHE_STORE=database  # or redis
SESSION_DRIVER=database  # or redis
```

## API Endpoints

### Public
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/register` | Register new user |
| POST | `/api/login` | Login |
| POST | `/api/guestLogin` | Guest login (auto‑create) |
| POST | `/api/forgetPassword` | Send reset link |
| GET | `/api/latestVersion` | App version check |
| GET | `/api/home` | Homepage content |
| GET | `/api/carTypes` | List car types |
| GET | `/api/engineTypes` | List engine types |
| GET | `/api/batteryVoltageTypes` | List battery voltages |
| GET | `/api/terms` | Terms & conditions |
| GET | `/api/about` | About & contact |

### Authenticated (Sanctum)
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/logout` | Logout |
| POST | `/api/deleteAccount` | Delete account |
| POST | `/api/resetPassword` | Change password |
| POST | `/api/updateLocale` | Set locale (en/ar) |
| POST | `/api/reportIssue` | Submit issue report |
| GET | `/api/profile` | Get profile |
| POST | `/api/profile` | Update profile |
| GET | `/api/cars` | List user cars |
| POST | `/api/car` | Add car |
| POST | `/api/newOrder` | Create order |
| GET | `/api/listOrders` | List user orders |
| POST | `/api/approveOrder` | Approve order |
| POST | `/api/cancelOrder` | Cancel order |
| GET | `/api/listNotifications` | List notifications |
| POST | `/api/approveNotifications` | Mark read |
| POST | `/api/cancelNotifications` | Dismiss |

## Key Models

- **User** — roles (user, admin, technician), locale, device tokens
- **Car** — VIN, license, brand/model/year, linked to user
- **Order** — status lifecycle, FCM push on status change
- **Invoice** / **Quotation** — auto PDF generation, Arabic support
- **Notification** — bilingual, admin/user sent, order‑linked
- **Service** / **Part** / **Emergency** — lookup + pricing

## Development Commands

```bash
# Start dev server + queue + Vite
composer dev

# Run tests
composer test

# Clear cache
php artisan optimize:clear

# Generate Filament assets
php artisan filament:upgrade
```

## Project Structure

```
app/
├── Console/           # Artisan commands
├── Filament/          # Admin panel (Resources, Pages, Widgets)
├── Http/
│   ├── Controllers/
│   │   └── Api/       # API controllers
│   └── Middleware/
├── Models/            # Eloquent models
├── Notifications/     # Mail notifications
├── Policies/          # Authorization policies
├── Providers/         # Service providers
├── Services/
│   └── FCMService.php # Firebase push service
└── Traits/
    └── Bilingual.php  # Arabic/English field accessor
```

## License

Proprietary — Click & Fix
