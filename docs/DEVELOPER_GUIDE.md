# JDCA Developer Guide

## Overview

This document is the main developer reference for the JDCA project.

JDCA is a Laravel 12 based college management system with:

- a public website
- a Filament admin panel
- a student portal
- a teacher portal
- PDF reporting
- queue-backed emails and notifications
- production hardening for Phase 1

Use this guide as the starting point for development, onboarding, debugging, testing, and deployment handoff.

Related project documentation:

- [ADMIN_GUIDE.md](file:///d:/Dashboard-J/docs/ADMIN_GUIDE.md)
- [REFERENCE.md](file:///d:/Dashboard-J/docs/REFERENCE.md)
- [phase-1-production-readiness.md](file:///d:/Dashboard-J/docs/phase-1-production-readiness.md)
- [DEPLOYMENT.md](file:///d:/Dashboard-J/docs/DEPLOYMENT.md)
- [sentry-monitoring.md](file:///d:/Dashboard-J/docs/sentry-monitoring.md)

## Product Scope

This project combines three major areas:

1. Public website for prospective students and visitors
2. Admin dashboard for academic and operational management
3. Student portal for results, fees, timetable, notices, and profile actions
4. Teacher portal for timetable, attendance, materials, assignments, notices, and profile actions

## Tech Stack

### Backend

- PHP 8.2+
- Laravel 12
- Eloquent ORM
- Laravel scheduler and queues

### Admin Panel

- Filament v3
- Filament Shield
- Spatie permissions

### Frontend

- Blade templates
- Tailwind CSS
- Alpine.js
- Vite

### Reporting and Utilities

- `barryvdh/laravel-dompdf`
- `mpdf/mpdf`
- `maatwebsite/excel`
- `pxlrbt/filament-excel`
- `sentry/sentry-laravel`

## Main Application Areas

### Public Website

Public website routes are defined in `routes/web.php`.

Main public pages:

- `/`
- `/about`
- `/about/history`
- `/about/mission`
- `/programs`
- `/faculty`
- `/admissions`
- `/contact`
- `/news`
- `/events`
- `/notices`
- `/results`
- `/timetable`
- `/gallery`

The public website is not a free-form page builder. It uses structured CMS-managed content:

- `WebsitePage` for editable static public pages
- `HomeSection` for homepage-managed sections
- `CollegeSetting` for branding, footer, theme, and common website settings

### Admin Panel

Admin panel uses Filament and is available at `/admin`.

Core admin resource groups include:

- students and admissions
- academic programs and departments
- courses and academic years
- teachers and timetable
- attendance and examinations
- fee structures and fee payments
- library books and book issues
- LMS materials and assignments
- announcements
- website pages, home sections, news, events
- lookup values and settings

### Student Portal

Student portal routes live under `/portal`.

Student features:

- login via roll number
- dashboard
- results
- fees
- fee challan access
- timetable
- notices
- profile
- password update

### Teacher Portal

Teacher portal routes live under `/teacher`.

Teacher features:

- login via `email` or `employee_id`
- dashboard
- timetable
- attendance sessions
- attendance marking with `present`, `absent`, and `late`
- teacher-owned LMS materials create and edit
- teacher-owned assignments create and edit
- notices
- profile
- password update

## Project Structure

Important directories:

- `app/Http/Controllers`: public, portal, and PDF controllers
- `app/Http/Middleware`: custom middleware like student auth and security headers
- `app/Filament`: admin resources, pages, widgets
- `app/Models`: domain models
- `app/Policies`: authorization policies
- `app/Services`: business logic services
- `app/Repositories`: repository layer used by services
- `app/Support`: shared support logic, such as admissions validation
- `app/Mail`: queued mailables
- `resources/views/public`: public pages
- `resources/views/components/home`: homepage components
- `resources/views/emails`: email templates
- `resources/views/pdf`: PDF templates
- `database/migrations`: schema history
- `database/seeders`: baseline and demo data
- `tests/Feature`: controller, public, portal, PDF, repository, and service tests
- `tests/Unit`: helper and enum tests
- `deploy`: Nginx, Supervisor, cron, and systemd templates
- `docs`: project documentation

## Architecture Notes

### Controller Pattern

The routed controller layer is intentionally thin and focused:

- `PublicController` handles public website rendering and public form submissions
- `StudentAuthController` handles student portal login and logout
- `StudentPortalController` handles portal business workflows
- `TeacherAuthController` handles teacher portal login and logout
- `TeacherPortalController` handles teacher portal business workflows
- `PdfController` handles authenticated PDF downloads

### Repository and Service Layer

Parts of the academic domain use repositories and services.

Examples:

- `StudentRepository` and `StudentService`
- `AcademicProgramRepository` and `AcademicProgramService`
- `DepartmentRepository` and `DepartmentService`

This project is not a strict full-repository architecture everywhere. Some domains still use direct Eloquent in controllers or resources where that is simpler and already stable.

### Models

Important models include:

- `Student`
- `Teacher`
- `AcademicProgram`
- `Department`
- `Course`
- `AcademicYear`
- `Semester`
- `Timetable`
- `AttendanceSession`
- `AttendanceRecord`
- `Exam`
- `ExamResult`
- `FeeStructure`
- `FeePayment`
- `Book`
- `BookIssue`
- `Announcement`
- `LmsMaterial`
- `LmsAssignment`
- `NewsArticle`
- `WebsiteEvent`
- `WebsitePage`
- `HomeSection`
- `CollegeSetting`
- `AdmissionInquiry`
- `ContactMessage`
- `User`

### Enums

The project uses many enums in `app/Enums` for consistency, validation, and UI labels.

Examples:

- status enums
- admission category enum
- fee type enum
- student status enum
- teacher status enum
- attendance status enum
- day-of-week enum

### Authorization

Authorization is layered:

- Laravel auth for admin users
- custom `auth.student` middleware for student portal users
- custom `auth.teacher` middleware for teacher portal users
- policies for admin-managed resources
- Filament Shield roles and permissions for panel access

## Authentication and Roles

### Admin Authentication

- Admin users use the default `web` guard
- Filament admin login is under `/admin/login`
- Guest redirects for protected admin PDF routes resolve to `/admin/login`

### Student Authentication

- Student portal uses custom student authentication flow
- Login identifier is `roll_number`
- Password uses `portal_password`
- If `portal_password` is null, the default password fallback is the student's roll number

### Teacher Authentication

- Teacher portal uses a separate custom teacher authentication flow
- Login identifier can be `email` or `employee_id`
- Password uses `portal_password`
- If `portal_password` is null, the default password fallback is the teacher's `employee_id`

### Roles

Current project roles and seeded examples are documented in [REFERENCE.md](file:///d:/Dashboard-J/docs/REFERENCE.md).

Typical roles:

- `super_admin`
- `admin`
- `student`
- `teacher`
- `Developer`
- `panel_user`

The exact usable admin-side roles depend on current Shield setup and seeded permissions.

Current permission behavior:

- only `super_admin` should have full access by default
- `Settings` visibility is permission-controlled
- dashboard navigation is hidden for users with no effective permissions
- after adding new Filament pages/resources or changing role visibility, regenerate Shield permissions and clear caches

## Local Development Setup

### Requirements

- PHP 8.2+
- Composer 2+
- Node.js 20+
- npm
- MySQL or SQLite for local use
- Redis optional for local, recommended for production

### First-Time Setup

From the project root:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm install
npm run build
php artisan storage:link
```

### Recommended Local Commands

```bash
composer dev
php artisan test
php artisan optimize:clear
```

`composer dev` starts:

- Laravel local server
- queue listener
- log tailing
- Vite dev server

### Local URLs

- Public website: `/`
- Admin panel: `/admin`
- Student portal login: `/portal/login`
- Teacher portal login: `/teacher/login`

## Environment Configuration

Use `.env.example` for local defaults and `.env.production.example` for production handoff.

Important environment areas:

- app identity and URL
- database
- session
- queue and cache
- Redis
- mail
- rate limits
- security headers and CSP
- notification recipient emails
- Sentry

### Important Custom ENV Values

```env
APP_FORCE_HTTPS=false
SECURITY_HEADERS_ENABLED=true
SECURITY_CSP_ENABLED=false
RATE_LIMIT_CONTACT_PER_MINUTE=5
RATE_LIMIT_ADMISSIONS_PER_MINUTE=40
RATE_LIMIT_STUDENT_LOGIN_PER_MINUTE=5
RATE_LIMIT_TEACHER_LOGIN_PER_MINUTE=5
CONTACT_NOTIFICATION_EMAIL=
ADMISSIONS_NOTIFICATION_EMAIL=
STUDENT_SUPPORT_EMAIL=
SEND_STUDENT_WELCOME_EMAIL=true
SENTRY_LARAVEL_DSN=
SENTRY_TRACES_SAMPLE_RATE=0.1
SENTRY_SEND_DEFAULT_PII=false
```

## Database, Migrations, and Seeders

### Migrations

Migrations are located in `database/migrations`.

Main schema areas include:

- users, roles, permissions
- academics
- students and teachers
- attendance
- examinations
- fees
- library
- LMS
- website CMS
- notifications
- inquiries and contact messages

### Seeders

Important seeders:

- `DatabaseSeeder`
- `MinimalCoreDataSeeder`
- `CollegeSettingSeeder`
- `ListItemSeeder`
- `WebsitePageSeeder`
- `HomeSectionSeeder`
- `TestCredentialsSeeder`

Use focused seeders when you need specific data restored without reseeding everything.

Examples:

```bash
php artisan db:seed --class=ListItemSeeder
php artisan db:seed --class=CollegeSettingSeeder
php artisan db:seed --class=TestCredentialsSeeder
```

### Seeded Access

Seeded credentials and quick operational references are documented in [REFERENCE.md](file:///d:/Dashboard-J/docs/REFERENCE.md).

Rotate all seeded credentials before production use.

## Public Website Content Management

### Static-but-Editable CMS Pattern

The public website uses a controlled CMS pattern:

- existing public pages are editable through admin
- pages are not arbitrary user-generated layouts
- homepage sections are managed separately from general pages

This was a deliberate project decision to keep the public website stable, easier to manage, and less error-prone than a generic page builder.

### Main CMS Models

- `WebsitePage`: content, visibility, images, page metadata
- `HomeSection`: homepage block content and active state
- `CollegeSetting`: shared website appearance and common text

### Admissions Form

Admissions form implementation includes:

- frontend and backend validation
- AJAX step validation
- inline field errors
- repopulation on reload
- admission category filtering
- shared rules through `App\Support\AdmissionValidation`

### Public Forms

Current public forms:

- contact form
- admission inquiry form

Both create records and queue mail notifications.

## Student Portal Business Rules

### Password Logic

- student logs in with roll number plus password
- if `portal_password` is null, roll number works as the default password
- when password changes, it is hashed through the model mutator
- password change queues a confirmation email if the student has an email address

### Portal Visibility Rules

Portal data is restricted to the authenticated student's own records:

- own fee payments only
- own challan only
- own timetable filters
- own published results only
- notices filtered by audience and expiry

## Emails, Notifications, and Queues

### Queued Mailables

Current queued email flows include:

- contact acknowledgement
- contact office notification
- admission acknowledgement
- admission office notification
- student portal welcome email
- student portal password changed email

### Queue Usage

Queue is required for reliable email delivery and background processing.

Recommended production setup:

- `QUEUE_CONNECTION=redis`
- process workers with Supervisor or systemd

### Notifications

Admin notifications currently include overdue fee alerts.

## Scheduler and Automation

Defined scheduled tasks:

- `fees:check-overdue` at `01:00`
- `queue:prune-failed --hours=72` at `01:30`
- `queue:prune-batches --hours=72` at `02:00`

Source: `routes/console.php`

### Production Scheduler Options

Use one:

- cron with `schedule:run`
- `systemd` with `schedule:work`

See [DEPLOYMENT.md](file:///d:/Dashboard-J/docs/DEPLOYMENT.md) for templates and commands.

## Security Implementation

### Current Phase 1 Hardening

- route rate limiting for public forms and student login
- global security headers middleware
- optional Content Security Policy via env
- optional forced HTTPS via env
- queued email notifications
- production env guidance
- Sentry support installed but inactive until DSN is configured

### Rate Limiters

Defined in `AppServiceProvider`:

- `public-contact`
- `public-admissions`
- `student-login`

### Middleware

Custom middleware includes:

- `SecurityHeaders`
- `StudentAuthenticated`

### Security Header Behavior

Security headers are appended globally from `bootstrap/app.php`.

Current supported behavior:

- `X-Content-Type-Options`
- `X-Frame-Options`
- `Referrer-Policy`
- `Permissions-Policy`
- optional `Content-Security-Policy`

## File Uploads and Media

Uploaded files should be served through the public storage symlink.

Important command:

```bash
php artisan storage:link
```

Common symptoms when storage is misconfigured:

- uploaded images not showing
- public page media not resolving
- admin previews not rendering as expected

## PDFs

Authenticated PDF routes:

- fee challan
- transcript
- attendance report
- exam result sheet

PDF templates live in `resources/views/pdf`.

PDF access is protected and validated for ownership or admin auth depending on route.

## Testing Strategy

### Current Test Coverage

Main test areas already implemented:

- public page behavior
- public forms and rate limits
- student auth flows
- student portal data isolation and business logic
- teacher portal auth, ownership rules, and attendance/materials/assignments workflows
- PDF controller downloads
- selected model, helper, repository, and service tests

### Key Test Suites

- `tests/Feature/Public`
- `tests/Feature/Portal`
- `tests/Feature/Pdf`
- `tests/Feature/Services`
- `tests/Feature/Repositories`
- `tests/Unit`

### Test Commands

Run all tests:

```bash
php artisan test
```

Run a focused suite:

```bash
php artisan test tests/Feature/Public
php artisan test tests/Feature/Portal
php artisan test tests/Feature/Pdf
```

### Testing Guidance

When changing routed behavior, prioritize feature tests over shallow unit tests.

Good candidates for tests:

- validation branches
- authorization
- data filtering
- publish visibility logic
- business logic side effects
- email dispatching
- PDF response headers

## Useful Developer Commands

### Development

```bash
composer dev
php artisan serve
npm run dev
php artisan queue:work
php artisan schedule:work
```

### Database

```bash
php artisan migrate
php artisan migrate:fresh --seed
php artisan db:seed
php artisan tinker
```

### Cache and Build

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
npm run build
```

### Permissions and Filament

```bash
php artisan shield:generate --all
php artisan filament:upgrade
```

### Queue and Scheduler

```bash
php artisan queue:restart
php artisan queue:prune-failed --hours=72
php artisan queue:prune-batches --hours=72
php artisan schedule:list
php artisan fees:check-overdue
```

## Deployment Handoff

For deployment, use:

- [DEPLOYMENT.md](file:///d:/Dashboard-J/docs/DEPLOYMENT.md)
- [phase-1-production-readiness.md](file:///d:/Dashboard-J/docs/phase-1-production-readiness.md)
- [sentry-monitoring.md](file:///d:/Dashboard-J/docs/sentry-monitoring.md)
- `.env.production.example`

Deployment templates already exist for:

- Nginx
- Supervisor
- cron
- systemd queue service
- systemd scheduler service

## Recommended Developer Workflow

1. Pull latest code
2. Install dependencies if needed
3. Run migrations
4. Seed required baseline data
5. Start local development stack
6. Make focused changes
7. Run targeted tests
8. Run full regression if the change is broad
9. Clear caches if behavior seems stale

## Common Maintenance Tasks

### Reset View and Route Issues

```bash
php artisan optimize:clear
```

### Restore Lookup Data

```bash
php artisan db:seed --class=ListItemSeeder
```

### Restore Settings Defaults

```bash
php artisan db:seed --class=CollegeSettingSeeder
```

### Restore Test Credentials

```bash
php artisan db:seed --class=TestCredentialsSeeder
```

## Troubleshooting

### Uploaded Images Do Not Show

- verify `php artisan storage:link`
- confirm the file is stored on the expected disk
- clear view and app cache if recent template changes were made

### Public Page Content Looks Stale

- clear caches
- verify the related `WebsitePage` or `HomeSection` record is active and published
- confirm the route is using the expected slug

### Student Cannot Log In

- verify roll number
- verify student is active
- check whether `portal_password` is set
- remember the fallback password can be the roll number when password is null
- check student login rate limiting if repeated attempts were made

### Emails Are Not Sending

- verify `MAIL_*` configuration
- verify queue worker is running
- check `storage/logs/laravel.log`
- if local uses `MAIL_MAILER=log`, confirm mail output in logs instead of inbox

### Scheduler Jobs Are Not Running

- verify cron or `systemd` scheduler is actually active
- run `php artisan schedule:list`
- test `php artisan schedule:run`

## Current Phase 1 Boundaries

Implemented and expected in Version 1:

- admin-managed student accounts
- public website CMS for existing pages
- homepage sections module
- admissions inquiry workflow
- contact workflow
- student portal
- queued emails
- rate limiting and headers
- deployment templates and production docs

Deferred to later phases:

- public student self-registration
- student email verification
- password reset by email for students
- advanced audit logging
- 2FA for admin users
- backup automation expansion

## Developer Onboarding Checklist

- read this guide
- read [REFERENCE.md](file:///d:/Dashboard-J/docs/REFERENCE.md)
- read [ADMIN_GUIDE.md](file:///d:/Dashboard-J/docs/ADMIN_GUIDE.md) for product behavior
- copy `.env.example` and configure local DB
- run migrations and seeders
- run `composer dev`
- log in to `/admin`
- test `/portal/login`
- review key public pages
- run `php artisan test`

## Recommended Next Documentation Improvements

Optional future documentation additions:

- API-style data model diagrams
- role-permission matrix export
- screenshot-based admin walkthrough
- content editing guide for website managers
- backup and restore playbook
- incident response checklist

## Summary

For day-to-day development, start with this file.

For production rollout, use [DEPLOYMENT.md](file:///d:/Dashboard-J/docs/DEPLOYMENT.md).

For admin operations, use [ADMIN_GUIDE.md](file:///d:/Dashboard-J/docs/ADMIN_GUIDE.md).

For seeded credentials and quick commands, use [REFERENCE.md](file:///d:/Dashboard-J/docs/REFERENCE.md).
