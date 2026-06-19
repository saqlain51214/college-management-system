# JDCA Deployment Guide

## Overview

This is the primary deployment guide for JDCA Version 1.

Use this document together with:

- [phase-1-production-readiness.md](file:///d:/Dashboard-J/docs/phase-1-production-readiness.md)
- [sentry-monitoring.md](file:///d:/Dashboard-J/docs/sentry-monitoring.md)

## Deployment Files Included

- Production env template: [.env.production.example](file:///d:/Dashboard-J/.env.production.example)
- Nginx template: [jdca.conf](file:///d:/Dashboard-J/deploy/nginx/jdca.conf)
- Supervisor queue template: [jdca-queue.conf](file:///d:/Dashboard-J/deploy/supervisor/jdca-queue.conf)
- Cron template: [jdca-scheduler.cron](file:///d:/Dashboard-J/deploy/cron/jdca-scheduler.cron)
- Systemd queue service: [jdca-queue.service](file:///d:/Dashboard-J/deploy/systemd/jdca-queue.service)
- Systemd scheduler service: [jdca-scheduler.service](file:///d:/Dashboard-J/deploy/systemd/jdca-scheduler.service)

## Recommended Production Stack

- Ubuntu 22.04 or newer
- Nginx
- PHP 8.2 with FPM
- MySQL 8 or MariaDB 10.6+
- Redis
- Node.js 20+
- Composer 2+
- SSL certificate

## First-Time Setup

### 1. Clone Project

```bash
git clone <repository-url> /var/www/jdca
cd /var/www/jdca
```

### 2. Install Dependencies

```bash
composer install --no-dev --optimize-autoloader
npm ci
```

### 3. Configure Environment

```bash
cp .env.production.example .env
php artisan key:generate --show
```

Then update `.env` with:

- real domain
- real database credentials
- real mail credentials
- Redis credentials
- Sentry DSN if using Sentry

### 4. Build And Migrate

```bash
php artisan storage:link
php artisan migrate --force
php artisan db:seed --force
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

## Nginx Setup

1. Copy [jdca.conf](file:///d:/Dashboard-J/deploy/nginx/jdca.conf) to your server.
2. Replace:
   - `your-domain.com`
   - SSL certificate paths
   - PHP-FPM socket path if needed
3. Enable the site:

```bash
sudo ln -s /etc/nginx/sites-available/jdca.conf /etc/nginx/sites-enabled/jdca.conf
sudo nginx -t
sudo systemctl reload nginx
```

## Queue Worker Setup

### Option A: Supervisor

Use [jdca-queue.conf](file:///d:/Dashboard-J/deploy/supervisor/jdca-queue.conf)

```bash
sudo cp deploy/supervisor/jdca-queue.conf /etc/supervisor/conf.d/jdca-queue.conf
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start jdca-queue:*
```

### Option B: systemd

Use [jdca-queue.service](file:///d:/Dashboard-J/deploy/systemd/jdca-queue.service)

```bash
sudo cp deploy/systemd/jdca-queue.service /etc/systemd/system/jdca-queue.service
sudo systemctl daemon-reload
sudo systemctl enable jdca-queue
sudo systemctl start jdca-queue
sudo systemctl status jdca-queue
```

## Scheduler Setup

### Option A: Cron

Use [jdca-scheduler.cron](file:///d:/Dashboard-J/deploy/cron/jdca-scheduler.cron)

```bash
crontab -e
```

Paste:

```bash
* * * * * cd /var/www/jdca && php artisan schedule:run >> /dev/null 2>&1
```

### Option B: systemd

Use [jdca-scheduler.service](file:///d:/Dashboard-J/deploy/systemd/jdca-scheduler.service)

```bash
sudo cp deploy/systemd/jdca-scheduler.service /etc/systemd/system/jdca-scheduler.service
sudo systemctl daemon-reload
sudo systemctl enable jdca-scheduler
sudo systemctl start jdca-scheduler
sudo systemctl status jdca-scheduler
```

Use one scheduler strategy only:

- cron
- or `systemd schedule:work`

## Redis Recommendation

For Version 1:

- use Redis for cache
- use Redis for queues
- use database sessions on single server
- use Redis sessions only if multiple app servers are planned

## Mail Setup

Recommended:

- SMTP
- Postmark
- Resend
- SES

Minimum required env:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.your-provider.com
MAIL_PORT=587
MAIL_USERNAME=no-reply@your-domain.com
MAIL_PASSWORD=change_me
MAIL_FROM_ADDRESS=no-reply@your-domain.com
MAIL_FROM_NAME="JDCA"
CONTACT_NOTIFICATION_EMAIL=info@your-domain.com
ADMISSIONS_NOTIFICATION_EMAIL=admissions@your-domain.com
STUDENT_SUPPORT_EMAIL=support@your-domain.com
```

## Sentry Setup

Sentry package is installed in the project.

Next steps:

1. Set DSN in `.env`
2. Clear and cache config
3. Trigger a test event in staging

```bash
php artisan config:clear
php artisan config:cache
php artisan sentry:test
```

See full setup notes in [sentry-monitoring.md](file:///d:/Dashboard-J/docs/sentry-monitoring.md)

## Deployment Command Sequence

For normal deployment:

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan down
php artisan migrate --force
npm ci
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan queue:restart
php artisan up
```

## Post-Deploy Checklist

- site loads over HTTPS
- admin login works
- student portal login works
- queue worker is running
- scheduler is running
- contact form sends email
- admission form sends email
- welcome student email sends correctly
- password change email sends correctly
- failed jobs table remains clean
- Sentry receives test event if enabled

## Important Notes

- Replace all placeholder domains before production.
- Do not keep `APP_DEBUG=true` in production.
- Do not email plain passwords.
- Rotate seeded test credentials after launch.
