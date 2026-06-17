# JDCA Dashboard — Quick Reference
**Jinnah School & Degree College Astore**
Last updated: 2026-06-16

---

## LOGIN CREDENTIALS

### Admin Panel → `/admin`

| Role | Email | Password | Access Level |
|------|-------|----------|--------------|
| super_admin | jinnahschooldegreecollege@gmail.com | admin@1234 | Full access — all modules, settings, permissions |
| Developer | developer@jdca.edu.pk | dev@1234 | All modules (technical role) |
| panel_user | staff@jdca.edu.pk | staff@1234 | Limited — assigned by super_admin |

### Student Portal → `/portal/login`

| Name | Roll Number | Password |
|------|-------------|----------|
| Muhammad Ali Khan | CS-2024-0001 | student@1234 |
| Fatima Zahra | CS-2024-0002 | student@1234 |
| Hamza Raza | CS-2023-0001 | student@1234 |
| Ayesha Siddiqi | CS-2023-0002 | student@1234 |
| Usman Tariq | CS-2022-0001 | student@1234 |

> **Default student password rule:** If no portal_password is set, the student's roll number itself is the password.
> Admin can reset any student password from: Admin Panel → Students → (key icon action) → Set Portal Password

---

## IMPORTANT ARTISAN COMMANDS

### Development Server
```bash
php artisan serve
# Runs at http://127.0.0.1:8000
```

### Database
```bash
php artisan migrate                   # Run new migrations
php artisan migrate --force           # Run in production (no prompt)
php artisan migrate:status            # See which migrations have run
php artisan migrate:rollback          # Undo last batch
php artisan db:seed                   # Run DatabaseSeeder
php artisan db:seed --class=TestCredentialsSeeder   # Restore test logins
```

### Cache / Performance
```bash
php artisan route:clear               # Clear route cache
php artisan view:clear                # Clear compiled blade views
php artisan cache:clear               # Clear application cache
php artisan config:clear              # Clear config cache
php artisan optimize:clear            # Clear ALL caches at once (use this after deploy)
php artisan optimize                  # Cache config+routes+views for production
```

### Fee Overdue Check (Manual Run)
```bash
php artisan fees:check-overdue
# Marks past-due fee payments as "overdue"
# Sends bell notification to super_admin + Developer users in admin panel
# Normally runs automatically at 01:00 AM daily (see Cron section below)
```

### Queue (if needed in future)
```bash
php artisan queue:work                # Process jobs
php artisan queue:restart             # Restart after code change
```

### Storage
```bash
php artisan storage:link
# Creates public/storage symlink — run this ONCE after fresh install
# Required for uploaded files (college logo, student photos, etc.) to be accessible
```

### Shield (Permissions)
```bash
# Generate permissions for ALL resources
php artisan shield:generate --all

# Generate permissions for specific resources only
php artisan shield:generate --resource="TimetableResource,AdmissionInquiryResource,ContactMessageResource"

# Assign super_admin role to a user by email
php artisan shield:super-admin --user=jinnahschooldegreecollege@gmail.com
```

---

## SCHEDULED TASKS (CRON JOBS)

### What runs automatically

| Command | Schedule | What it does |
|---------|----------|--------------|
| `fees:check-overdue` | Daily at 01:00 AM | Marks past-due payments as overdue, sends admin bell notification |

### Setting up the Cron Job on server (Linux/cPanel)

Add ONE single cron entry on your hosting server. This single entry runs all Laravel scheduled tasks:

```bash
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

**Replace `/path/to/your/project`** with your actual project path, e.g.:
```bash
* * * * * cd /home/youraccount/public_html && php artisan schedule:run >> /dev/null 2>&1
```

**On cPanel:** Go to Cron Jobs → Add New → set interval to "Every Minute (*)" → paste the command above.

### Test the scheduler locally (Windows)
```bash
php artisan schedule:run     # Run scheduled tasks that are due right now
php artisan schedule:list    # See all scheduled tasks and their next run time
```

### Defined in `routes/console.php`:
```php
Schedule::command('fees:check-overdue')->dailyAt('01:00');
```

---

## FILAMENT SHIELD — PERMISSIONS

Filament Shield manages role-based access to the admin panel.

### Roles in this project

| Role | Purpose |
|------|---------|
| `super_admin` | Full access to everything, bypasses all permission checks |
| `Developer` | All modules — technical/developer role |
| `panel_user` | Restricted access — admin assigns specific permissions |

### Key commands

```bash
# Regenerate permissions after adding new Filament resources
php artisan shield:generate --all

# Make a user super_admin
php artisan shield:super-admin --user=EMAIL_HERE

# List all roles and permissions
php artisan tinker
>>> \Spatie\Permission\Models\Role::with('permissions')->get()->each(fn($r) => dump($r->name, $r->permissions->pluck('name')));
```

### Assigning roles via Admin Panel
Admin Panel → Users → Edit → Role (select from dropdown)

### After adding new Filament resources
New resources won't appear for non-super_admin roles until you run:
```bash
php artisan shield:generate --resource="NewResourceName"
```
> **Note:** `super_admin` always sees all resources automatically without running this command.

---

## KEY URLs

| Page | URL |
|------|-----|
| College Website Home | `/` |
| About | `/about` |
| Programs | `/programs` |
| Faculty | `/faculty` |
| Admissions (with inquiry form) | `/admissions` |
| News | `/news` |
| Notice Board | `/notices` |
| Results Lookup | `/results` |
| Contact | `/contact` |
| **Admin Panel** | `/admin` |
| **Student Portal Login** | `/portal/login` |
| Student Dashboard | `/portal` |
| Student Results | `/portal/results` |
| Student Fees | `/portal/fees` |
| Student Timetable | `/portal/timetable` |
| Student Notices | `/portal/notices` |

### PDF Downloads (requires admin login)
| Document | URL |
|----------|-----|
| Fee Challan | `/pdf/challan/{payment_id}` |
| Student Transcript | `/pdf/transcript/{student_id}` |
| Attendance Report | `/pdf/attendance/{student_id}` |
| Exam Result Sheet | `/pdf/exam-results/{exam_id}` |

---

## AFTER FRESH INSTALL / DEPLOYMENT CHECKLIST

Run these commands in order on a new server:

```bash
# 1. Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# 2. Environment setup
cp .env.example .env
php artisan key:generate

# 3. Configure .env — set DB_*, MAIL_*, APP_URL

# 4. Database
php artisan migrate --force
php artisan db:seed

# 5. Storage symlink (for uploaded files)
php artisan storage:link

# 6. Permissions (Filament Shield)
php artisan shield:generate --all
php artisan shield:super-admin --user=jinnahschooldegreecollege@gmail.com

# 7. Cache for production
php artisan optimize

# 8. Set up cron job (see Cron section above)
```

---

## EMAIL / MAIL CONFIGURATION (.env)

To enable email notifications (contact form, admission inquiry alerts):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com          # or your hosting SMTP
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your_app_password   # Gmail: use App Password, not account password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your@gmail.com
MAIL_FROM_NAME="JDCA Astore"
```

After updating .env:
```bash
php artisan config:clear
php artisan cache:clear
```

---

## DATABASE TABLES ADDED BY THIS PROJECT

| Table | Purpose |
|-------|---------|
| `timetables` | Class schedule (program, semester, day, time, room) |
| `admission_inquiries` | Admission inquiry form submissions from website |
| `contact_messages` | Contact form submissions from website |
| `students.portal_password` | Hashed password for student portal login |
| `students.remember_token` | Laravel auth remember-me token |

---

## STUDENT PORTAL — HOW IT WORKS

- URL: `/portal/login`
- Guard: `student` (separate from admin `web` guard)
- Default password: student's roll number (if no portal_password set)
- Admin sets password: Admin Panel → Students → key icon → Set Portal Password
- Sessions are independent from admin panel (student can be logged in while admin is also logged in)

---

## NOTES & KNOWN ISSUES

1. **shield:generate in non-interactive mode** — The command fails with a TypeError in some environments. Always run it directly in the terminal (not via queue or script). Super_admin bypasses this and can access all resources anyway.

2. **Fee PDF in student portal** — Uses route `/portal/fees/{payment}/challan` (student-specific route with ownership check). The admin PDF route `/pdf/challan/{id}` still requires admin login.

3. **Attendance module** — Attendance data is in the database (AttendanceSeeder has run) but an `Attendance` model file may need to be created if you want to add an attendance page to the student portal.

4. **Timetable** — The `timetables` table already existed from the TimetableSeeder. Add entries via Admin Panel → Academics → Timetable.
