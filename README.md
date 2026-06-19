# JDCA College Management System

JDCA is a Laravel 12 based college management system for Jinnah School & Degree College Astore.

It includes:

- public website
- Filament admin panel
- student portal
- PDF reports
- queue-backed emails
- Phase 1 production hardening

## Quick Start

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm install
npm run build
php artisan storage:link
composer dev
```

## Main URLs

- Public website: `/`
- Admin panel: `/admin`
- Student portal: `/portal/login`

## Main Documentation

- Developer guide: [DEVELOPER_GUIDE.md](file:///d:/Dashboard-J/docs/DEVELOPER_GUIDE.md)
- Admin guide: [ADMIN_GUIDE.md](file:///d:/Dashboard-J/docs/ADMIN_GUIDE.md)
- Quick reference: [REFERENCE.md](file:///d:/Dashboard-J/docs/REFERENCE.md)
- Deployment: [DEPLOYMENT.md](file:///d:/Dashboard-J/docs/DEPLOYMENT.md)
- Production readiness: [phase-1-production-readiness.md](file:///d:/Dashboard-J/docs/phase-1-production-readiness.md)
- Sentry monitoring: [sentry-monitoring.md](file:///d:/Dashboard-J/docs/sentry-monitoring.md)

## Core Commands

```bash
composer dev
php artisan test
php artisan optimize:clear
php artisan queue:work
php artisan schedule:work
```

## Stack

- Laravel 12
- PHP 8.2+
- Filament 3
- Filament Shield
- Tailwind CSS
- Alpine.js
- Vite
- MySQL or SQLite
- Redis recommended for production

## Notes

- Use `.env.example` for local setup
- Use `.env.production.example` for production setup
- Rotate seeded credentials before production
