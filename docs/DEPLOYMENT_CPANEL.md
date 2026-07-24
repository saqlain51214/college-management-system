# Deploying JDCA to HosterPK (cPanel) — Step-by-Step

Domain: **jinnahdegreecollegeastore.com** · Host: **HosterPK, PHP Hosting Plan II** (cPanel, SSH, cron jobs, PHP version selector, MySQL).

> ⚠️ **First thing — rotate your cPanel password.** It was shared in a chat transcript, which is not a secure channel to keep it in long-term. After you finish this guide, go to cPanel → **Password & Security** and set a new password. Do the same for any email account password you create below.

---

## 0. Before you start

- Your SSL is already active (good — HosterPK confirmed it). Nothing to do there.
- This guide assumes **cPanel → Terminal (SSH)** is available on your plan (confirmed earlier — Plan II lists "Cron Jobs / SSH Shell Access"). If Terminal isn't visible in your cPanel, open a HosterPK support ticket asking them to enable SSH access for your account — everything below needs it (Composer, artisan commands).
- Do not commit `.env`, real credentials, or `vendor/`/`node_modules/` to git — the repo already ignores these.

---

## 1. Where the Laravel app lives vs. what's public

Shared hosting only serves whatever is inside `public_html/`. A Laravel app's `public_html` should contain **only** the contents of Laravel's own `public/` folder — the rest of the app (with `.env`, `app/`, `vendor/`, etc.) must sit **outside** `public_html` so the internet can never reach it directly.

**Layout on the server:**
```
/home/jinnahde/
├── jdca_app/            ← the whole Laravel project (git clone target)
│   ├── app/ database/ routes/ .env  ...
│   └── public/          ← Laravel's own public folder (NOT web-served directly)
└── public_html/         ← what the domain actually serves
    ├── index.php        ← modified to point into ../jdca_app
    └── (mirrors the rest of jdca_app/public/*)
```

Check first whether cPanel → **Domains** lets you set a custom **Document Root** for `jinnahdegreecollegeastore.com` (many modern cPanel installs allow this even for the primary domain). If it does, this is cleaner — skip the "mirrors" step and just point Document Root straight at `/home/jinnahde/jdca_app/public`. If that field is greyed out/unavailable, use the two-folder layout above (steps below assume this fallback).

---

## 2. Create the database (cPanel → MySQL® Databases)

1. **MySQL Databases** → under "Create New Database", name it e.g. `jdca` → cPanel will create it as `jinnahde_jdca`.
2. Under "MySQL Users", create a user (e.g. `jdcauser` → becomes `jinnahde_jdcauser`) with a **strong password** — save it, you'll need it in `.env`.
3. Under "Add User To Database", add that user to `jinnahde_jdca` with **ALL PRIVILEGES**.
4. Note the three values: `jinnahde_jdca`, `jinnahde_jdcauser`, the password.

---

## 3. Create the email mailbox (cPanel → Email Accounts)

1. **Email Accounts** → Create → `noreply@jinnahdegreecollegeastore.com` (used for all system emails — OTPs, fee/notification mails). Set a strong password.
2. Optionally also create `info@…`, `admissions@…`, `support@…` if you want separate inboxes for those notification recipients (or just reuse `noreply@…` for all three — simplest to start).
3. From the same page, "Connect Devices" shows the exact SMTP host/port/encryption cPanel wants you to use for *that specific mailbox* — use those exact values in `.env` (the numbers in your welcome email, port 26, are an alternate SMTP port; port 465 SSL or 587 TLS usually also work — cPanel's own "Connect Devices" screen tells you definitively which to use).

---

## 4. Get the code onto the server (via SSH)

```bash
ssh jinnahde@jinnahdegreecollegeastore.com     # or the IP HosterPK gave you
cd ~
git clone https://github.com/<your-github-username>/college-management-system.git jdca_app
cd jdca_app
git checkout main
```

If `git` isn't available or GitHub access is blocked, use cPanel's **Git Version Control** feature instead (Setup a new repository, paste your GitHub URL), or upload a zip via **File Manager** and extract it into `~/jdca_app`.

### Install PHP dependencies + build assets

```bash
composer install --no-dev --optimize-autoloader
```

Front-end assets (`npm run build`) need Node, which shared hosting usually doesn't have. Build them **locally on your machine** first and commit `public/build/` (or upload it via FTP after building locally):

```powershell
# on your own computer, in the project folder
npm ci
npm run build
# then upload the generated public/build/ folder to the server (FTP or git)
```

### Set the PHP version

cPanel → **MultiPHP Manager** → select `jinnahdegreecollegeastore.com` → set to **PHP 8.3** (must be 8.2+).

### Point the web root at `public/`

- **If Document Root is configurable:** cPanel → Domains → set Document Root to `/home/jinnahde/jdca_app/public`. Done — skip to step 5.
- **If not configurable (fallback):** copy the *contents* of `jdca_app/public/` into `public_html/`, then edit `public_html/index.php`:
  ```php
  require __DIR__.'/../jdca_app/vendor/autoload.php';
  $app = require_once __DIR__.'/../jdca_app/bootstrap/app.php';
  ```
  (Laravel's stock `index.php` already has these two lines — just change `/../vendor` → `/../jdca_app/vendor` and `/../bootstrap` → `/../jdca_app/bootstrap`.) Do this again after every future deploy that regenerates `public/`.

---

## 5. Configure `.env`

```bash
cd ~/jdca_app
cp .env.cpanel.example .env
nano .env       # fill in DB_*, MAIL_*, APP_URL — see the template's comments
php artisan key:generate --show     # paste the output into APP_KEY= in .env
```

Fill in from steps 2 & 3: `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `MAIL_HOST`/`MAIL_PORT`/`MAIL_SCHEME`/`MAIL_USERNAME`/`MAIL_PASSWORD`.

---

## 6. Run migrations, link storage, cache config

```bash
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

This runs the same `ProductionSeeder`/`ShieldSeeder`/`DemoContentSeeder` logic that's already wired into the app for a fresh database (super admin, permissions, sample content) — no separate seeding command needed on first run. (If you'd rather run it explicitly: `php artisan db:seed --class=Database\\Seeders\\ProductionSeeder --force`.)

Log in once at `https://jinnahdegreecollegeastore.com/admin` with `admin@admin.com` / `admin123` and **change that password immediately** (Roles page → edit the super admin user).

---

## 7. Cron jobs (cPanel → Cron Jobs)

Laravel's scheduler (which runs the daily overdue-fee check, attendance reminders, etc.) needs one cron entry that fires every minute:

```
* * * * * cd /home/jinnahde/jdca_app && php artisan schedule:run >> /dev/null 2>&1
```

Add this exactly as one Cron Job entry in cPanel (Common Settings: "Once Per Minute").

---

## 8. Security checklist (production)

- [ ] `APP_DEBUG=false` and `APP_ENV=production` in `.env` (already set above)
- [ ] `.env` sits **outside** `public_html` (guaranteed by the layout in step 1)
- [ ] Rotate the cPanel password and the mailbox password (see the warning at the top)
- [ ] `SESSION_SECURE_COOKIE=true`, `APP_FORCE_HTTPS=true` — forces HTTPS-only cookies/redirects (already in the template)
- [ ] `public/.htaccess` (already in the repo) prevents directory listing and hides sensitive paths — don't remove it
- [ ] Set up automatic backups: cPanel → **Backup Wizard** → schedule weekly full-account backups
- [ ] Turn on **cPHulk / IP Blocker** (under Security in cPanel) if available, to block repeated failed logins to cPanel itself
- [ ] Keep `composer.lock` committed so dependency versions don't drift unexpectedly between deploys
- [ ] Review Filament **Roles** (`/admin/shield/roles`) — make sure only trusted people have `super_admin`

---

## 9. SEO checklist (already wired into the codebase)

- ✅ `robots.txt` allows crawling, blocks admin/portal/teacher/search/fee-challan, and points to `/sitemap.xml`
- ✅ `/sitemap.xml` is a live route — auto-lists all public pages + published news + visible departments
- ✅ Meta description tag exists on every public page (`@yield('meta_description', ...)`) — you can set a custom one per Website Page from **Website Pages → SEO Description** in the admin
- After going live: submit the site to **Google Search Console** (search.google.com/search-console) and submit `https://jinnahdegreecollegeastore.com/sitemap.xml` there — this gets the college indexed faster than waiting for Google to find it on its own
- Optional, after the site is stable: enable **Cloudflare** (free tier) as HosterPK's welcome email suggests — this needs changing your domain's nameservers to Cloudflare's. Do this only once everything above is working, since it's an extra moving part; it adds a CDN + basic DDoS/WAF protection on top of what's already here.

---

## 10. Staging environment

Create a second, isolated copy so you can test changes before they reach the real site:

1. cPanel → **Subdomains** → create `staging.jinnahdegreecollegeastore.com`, document root `/home/jinnahde/jdca_staging/public` (or the mirrored `public_html` fallback under a separate folder, same trick as step 1/4).
2. Clone the code again into a **separate** folder:
   ```bash
   git clone https://github.com/<your-username>/college-management-system.git jdca_staging
   cd jdca_staging && git checkout develop      # staging tracks the `develop` branch
   ```
3. Create a **separate MySQL database** for staging (repeat step 2 with a different DB name, e.g. `jinnahde_jdca_stg`) — never point staging at the real production database.
4. Copy `.env.cpanel.example` → `.env` for staging too, but set:
   - `APP_ENV=staging`
   - `APP_URL=https://staging.jinnahdegreecollegeastore.com`
   - the staging DB credentials from step 3
   - `MAIL_MAILER=log` (so test emails don't actually send to real people)
5. Add a **separate cron entry** pointing at `jdca_staging` (same one-per-minute pattern, different path).
6. Add to staging's `public/robots.txt`: `Disallow: /` (so Google never indexes the staging copy), and optionally password-protect the whole subdomain via cPanel → **Directory Privacy**.

**Workflow going forward:** push feature work to `develop` → it lands on staging automatically (or via `git pull` + the deploy steps 4/6 above) → once verified, merge `develop` → `main` and repeat steps 4/6 on the production folder.

---

## Quick reference — redeploying after a code change

```bash
ssh jinnahde@jinnahdegreecollegeastore.com
cd ~/jdca_app
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
```
(Re-upload `public/build/` too, if front-end assets changed — see step 4.)
