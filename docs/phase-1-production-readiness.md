# Phase 1 Production Readiness Guide

## Scope

This document defines what is required for Version 1 production deployment of the JDCA application, what is recommended, and what can safely wait for a later phase.

## Phase 1 Decision Summary

### Must Have In V1

- HTTPS enabled in production
- `APP_DEBUG=false`
- secure session/cookie settings
- rate limiting on public form submissions and student login
- queued email delivery for public forms and student account emails
- failed job logging and queue pruning
- security headers
- production mail configuration
- cache/queue/session strategy documented
- scheduled tasks configured on the server
- basic incident and retry visibility through logs and failed jobs

### Recommended In V1

- Redis for cache and queues
- Redis for sessions if the project is deployed on multiple app servers
- separate mailbox addresses for admissions and contact form notifications
- mail failover strategy
- supervisor or service management for queue workers
- automated database backups
- uptime monitoring
- error monitoring such as Sentry, Bugsnag, or equivalent

### Not Required For Current V1

- student self-signup flow
- student email verification for registration
- password reset by email for students
- real-time websockets
- full event/listener architecture for every action
- SMS integration
- advanced WAF or bot scoring

These can be added in Phase 2 once self-service student onboarding starts.

## Current V1 Security Measures Implemented

- Public contact form rate limiting
- Online admission form rate limiting
- Student portal login rate limiting
- Global security headers middleware
- Optional Content Security Policy support through env
- Optional HTTPS forcing through env
- Queued mail templates for:
  - contact acknowledgement
  - contact office notification
  - admission acknowledgement
  - admission office notification
  - student portal welcome email
  - student password changed email
- scheduler tasks for:
  - overdue fee checks
  - failed queue pruning
  - job batch pruning

## How Student Receives Email

### Current V1 Flow

- Student accounts are created by admin, not by self-registration.
- When a student is created and has an email address, the system can send a welcome email.
- The email contains:
  - portal login URL
  - student roll number
  - instruction that the default password is the roll number unless admin shared a separate temporary password
- When a student changes portal password, a password-changed confirmation email is sent.

### Why Email Verification Is Not Mandatory Right Now

- The current product does not allow public student registration.
- Student identities are created by college administration.
- Because of that, email verification is not essential for Phase 1.

### When To Add Student Email Verification

Add email verification only when one of these becomes part of the product:

- public student signup
- self-service password reset by email
- student onboarding through invitation links
- application tracking by student email account

## Infrastructure Recommendations

### Minimum Production Stack

- PHP 8.2+
- MySQL or MariaDB
- Nginx or Apache
- Supervisor or systemd for queue workers
- cron
- SSL certificate

### Recommended Production Stack

- PHP 8.2+
- MySQL 8 or MariaDB 10.6+
- Redis
- Nginx
- Supervisor
- cron
- SSL certificate

## Redis Recommendation

### Use Redis In V1 For

- cache
- queue

### Use Redis For Sessions If

- you run multiple app servers
- you expect horizontal scaling

### If Single Server

Single-server deployment can still use:

- `CACHE_STORE=redis`
- `QUEUE_CONNECTION=redis`
- `SESSION_DRIVER=database`

This is a very good Phase 1 balance.

## Required ENV Configuration

Below are the important env variables a developer or DevOps engineer must configure.

### Core App

```env
APP_NAME="JDCA"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
APP_KEY=base64:GENERATED_KEY
APP_FORCE_HTTPS=true
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
```

### Database

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jdca
DB_USERNAME=jdca_user
DB_PASSWORD=strong_password_here
```

### Session

#### Single Server

```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

#### Multi Server

```env
SESSION_DRIVER=redis
SESSION_STORE=redis
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

### Cache And Queue

#### Recommended

```env
CACHE_STORE=redis
QUEUE_CONNECTION=redis
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_CACHE_CONNECTION=cache
REDIS_QUEUE_CONNECTION=default
REDIS_QUEUE=default
QUEUE_FAILED_DRIVER=database-uuids
```

#### Acceptable Fallback

```env
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### Mail

#### SMTP Example

```env
MAIL_MAILER=smtp
MAIL_SCHEME=tls
MAIL_HOST=smtp.your-provider.com
MAIL_PORT=587
MAIL_USERNAME=no-reply@your-domain.com
MAIL_PASSWORD=your_smtp_password
MAIL_FROM_ADDRESS=no-reply@your-domain.com
MAIL_FROM_NAME="JDCA"
```

#### Notification Routing

```env
CONTACT_NOTIFICATION_EMAIL=info@your-domain.com
ADMISSIONS_NOTIFICATION_EMAIL=admissions@your-domain.com
STUDENT_SUPPORT_EMAIL=support@your-domain.com
SEND_STUDENT_WELCOME_EMAIL=true
```

### Security

```env
SECURITY_HEADERS_ENABLED=true
SECURITY_CSP_ENABLED=true
RATE_LIMIT_CONTACT_PER_MINUTE=5
RATE_LIMIT_ADMISSIONS_PER_MINUTE=40
RATE_LIMIT_STUDENT_LOGIN_PER_MINUTE=5
```

If CSP breaks front-end assets, temporarily keep:

```env
SECURITY_CSP_ENABLED=false
```

Then adjust the policy and enable it again.

## Mail Configuration Recommendation

### For V1

- Use SMTP or a transactional provider such as Postmark, Resend, SES, or Mailgun.
- Use a dedicated `no-reply` sender address.
- Use separate notification inboxes for:
  - admissions
  - contact form
  - support

### Good Practice

- Do not send plain passwords by email.
- For student welcome email, send login instructions and roll number.
- If admin chooses a custom temporary password, share it through a secure manual process instead of email.

## Scheduler Setup

### Commands Already Relevant In V1

- `fees:check-overdue`
- `queue:prune-failed --hours=72`
- `queue:prune-batches --hours=72`

### Cron Entry

```bash
* * * * * cd /var/www/jdca && php artisan schedule:run >> /dev/null 2>&1
```

## Queue Worker Setup

### Recommended Worker Command

```bash
php artisan queue:work --queue=default --tries=3 --timeout=120
```

### Supervisor Example

```ini
[program:jdca-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/jdca/artisan queue:work --queue=default --tries=3 --timeout=120 --sleep=3
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/jdca/storage/logs/queue-worker.log
stopwaitsecs=3600
```

## Deployment Commands

Run these on deployment:

```bash
composer install --no-dev --optimize-autoloader
php artisan down
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
npm ci
npm run build
php artisan up
```

If queue workers are running, restart them after deployment:

```bash
php artisan queue:restart
```

## First-Time Server Setup Commands

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate --force
php artisan db:seed --force
npm install
npm run build
```

For database-backed cache/session/queue, make sure these tables exist:

```bash
php artisan session:table
php artisan cache:table
php artisan queue:table
php artisan queue:batches-table
php artisan queue:failed-table
php artisan migrate
```

Only run the table-generation commands if those migrations do not already exist in the project.

## Commands Developers Should Know

### Local Development

```bash
composer dev
php artisan test
php artisan queue:work
php artisan schedule:work
```

### Useful Maintenance

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
php artisan queue:prune-failed --hours=72
php artisan queue:prune-batches --hours=72
php artisan fees:check-overdue
```

## Developer Checklist Before Production

- Confirm `APP_DEBUG=false`
- Confirm HTTPS is active
- Confirm `APP_URL` is correct
- Confirm mail sending works
- Confirm queue worker is running
- Confirm cron is running
- Confirm storage symlink exists
- Confirm contact/admission emails are delivered
- Confirm failed jobs table is monitored
- Confirm backups exist
- Confirm admin credentials are rotated after launch

## Final V1 Recommendation

### Implement Now

- rate limiting
- security headers
- queued mail templates
- SMTP or transactional mail setup
- Redis for cache and queue
- scheduler and queue worker setup
- production env hardening
- student welcome email on admin-created accounts
- password change notification email

### Plan Next

- student self-registration
- email verification
- forgot password by email
- admin audit logs
- external error monitoring
- backup automation
- 2FA for admin users

This gives a strong and realistic Phase 1 production baseline without overcomplicating the first release.
