# JDCA College Management System — Administrator Guide

**Jinnah School & Degree College Astore (JDCA)**  
Principal: Arif Ali | Astore, Gilgit-Baltistan 14300  
Affiliation: Karakoram International University  

---

## Table of Contents

1. [System Overview](#1-system-overview)
2. [Logging In & Navigation](#2-logging-in--navigation)
3. [Dashboard](#3-dashboard)
4. [Students & Admissions](#4-students--admissions)
5. [Teachers & Staff](#5-teachers--staff)
6. [Academic Programs & Departments](#6-academic-programs--departments)
7. [Courses](#7-courses)
8. [Timetable](#8-timetable)
9. [Attendance](#9-attendance)
10. [Examinations & Results](#10-examinations--results)
11. [Fee Management](#11-fee-management)
12. [Library](#12-library)
13. [LMS Portal](#13-lms-portal)
14. [News & Updates](#14-news--updates)
15. [Announcements](#15-announcements)
16. [Reports & PDF Documents](#16-reports--pdf-documents)
17. [System Settings](#17-system-settings)
18. [User Management & Roles](#18-user-management--roles)
19. [Artisan Commands (IT Admin)](#19-artisan-commands-it-admin)
20. [Scheduled Tasks & Automation](#20-scheduled-tasks--automation)
21. [Troubleshooting & FAQs](#21-troubleshooting--faqs)
22. [Business Logic Reference](#22-business-logic-reference)

---

## 1. System Overview

The JDCA College Management System is a web-based administration panel for managing all academic and administrative operations of Jinnah School & Degree College Astore. It is built on Laravel 12 and the Filament v3 admin framework.

**Core capabilities:**
- Student enrollment and lifecycle management
- Teacher profiles and course assignments
- Timetable scheduling (Monday–Saturday)
- Attendance tracking per session with per-course reporting
- Examination management and automated grade calculation
- Fee structures, challan generation, and payment recording
- Library book catalog, issue/return tracking, and fines
- LMS: course materials, assignments, and announcements
- PDF reports: transcripts, attendance reports, result sheets, fee challans
- Automated fee overdue detection with admin notifications
- Fully configurable settings including college logo, branding, and dropdown values

---

## 2. Logging In & Navigation

**URL:** `http://localhost/dashboard/admin`  
*(Replace `localhost/dashboard` with your server address after deployment)*

**Login credentials:**  
Use the email and password created during setup. Contact the IT administrator for credentials.

### Navigation Structure

The left sidebar organizes all modules into groups:

| Group | Modules |
|-------|---------|
| **Dashboard** | Overview, Stats, Charts |
| **Students & Admissions** | Students, Academic Programs, Fee Structures, Fee Payments |
| **Academic** | Teachers, Departments, Courses, Academic Years, Timetable |
| **Attendance** | Attendance Sessions, Attendance Records |
| **Examinations** | Exams, Exam Results |
| **Library** | Books, Book Issues |
| **LMS Portal** | Course Materials, Assignments, Announcements |
| **System** | News & Updates, Lookup Values, Settings |

**Topbar icons:**
- **Bell icon** — notifications (fee overdue alerts, system messages)
- **User avatar** — profile, logout

---

## 3. Dashboard

The dashboard is the first screen after login. It shows real-time statistics and charts.

### Widgets Explained

| Widget | Description |
|--------|-------------|
| **Total Students** | Count of all enrolled students |
| **Total Teachers** | Active teaching staff count |
| **Total Courses** | Courses in the system |
| **Fee Collected (Month)** | Total payments received this calendar month |
| **Student Enrollment Chart** | Monthly enrollment trend (bar chart, last 12 months) |
| **Attendance Chart** | Present/Absent/Late distribution (donut chart) |
| **Recent Fee Payments** | Last 6 fee payments with status badges |
| **Library Stats** | Total books, available, issued today |
| **Top Performers** | Top 8 students by CGPA (average grade points) |
| **Today's Classes** | Timetable entries for today's day of week |

> **Note:** Each widget is a separate component. If a widget shows 0 or empty data, it means no records have been entered for that module yet.

---

## 4. Students & Admissions

**Path:** Sidebar → Students & Admissions → Students

### Adding a New Student

1. Click **New Student** (top right)
2. Fill in the **Personal Information** section:
   - Full Name (required)
   - Father's Name
   - CNIC or B-Form number (format: `XXXXX-XXXXXXX-X`)
   - Date of Birth, Gender
   - Phone, Email, Address
   - Province (dropdown — configurable in Settings → Lookup Values)
3. Fill in the **Academic Information** section:
   - Roll Number (auto-generated as `DEPT-YEAR-XXXX`, e.g. `CS-2024-0001`)
   - Registration Number
   - Academic Program (select from list)
   - Academic Year
   - Batch Year (e.g. 2024)
   - Current Semester (1–8)
   - Admission Date
   - Status: `Active`, `Graduated`, `Dropped`, `Suspended`, `On Leave`
4. Fill in **Previous Education** (optional):
   - Previous Qualification (dropdown — configurable)
   - Previous Institution, Board/University, Result/Marks
5. Click **Save**

### Student Status Values

| Status | Meaning |
|--------|---------|
| **Active** | Currently enrolled and attending |
| **Graduated** | Completed the program |
| **Dropped** | Left without completing |
| **Suspended** | Temporarily suspended |
| **On Leave** | Approved leave of absence |

### PDF Actions on Student Records

In the Students table, each row has icon buttons:

| Icon | Action |
|------|--------|
| Document arrow | **Print Transcript** — full academic transcript with results and attendance |
| Chart bar | **Attendance Report** — course-wise attendance breakdown PDF |
| Edit | Edit student record |
| Trash | Delete student |

### Searching & Filtering

Use the search bar (top of table) to search by name, roll number, CNIC, or email. Use filters to narrow by:
- Program, Academic Year, Semester
- Status, Gender, Province

---

## 5. Teachers & Staff

**Path:** Sidebar → Academic → Teachers

### Adding a Teacher

1. Click **New Teacher**
2. Fill in:
   - Name (required)
   - CNIC, Phone, Email
   - Designation (dropdown — configurable: Lecturer, Assistant Professor, etc.)
   - Highest Qualification (dropdown — configurable)
   - Specialization, Experience Years
   - Joining Date
   - Province, Address
   - Status: Active / Inactive
3. Assign courses in the **Course Assignments** section (after saving the teacher)
4. Click **Save**

---

## 6. Academic Programs & Departments

**Path:** Sidebar → Academic → Academic Programs / Departments

### Academic Programs

Programs define degree courses offered (e.g. BS Computer Science, FSc Pre-Medical).

| Field | Description |
|-------|-------------|
| Name | Full program name |
| Short Name | Abbreviation (e.g. BS-CS) |
| Code | Unique code |
| Duration (Years) | 2 or 4 years |
| Total Semesters | Usually 4 or 8 |
| Level | Undergraduate, Intermediate, etc. |
| Is Active | Whether open for enrollment |

### Departments

Departments group programs (e.g. Department of Computer Science, Department of Sciences).

---

## 7. Courses

**Path:** Sidebar → Academic → Courses

### Adding a Course

1. Click **New Course**
2. Fill in:
   - Course Name (required)
   - Course Code (e.g. CS-101)
   - Credit Hours
   - Academic Program (which program this belongs to)
   - Semester Number (1–8)
   - Course Type: Core / Elective / Lab / Practical
   - Description (optional)
   - Is Active toggle
3. Click **Save**

> Courses must be created before creating timetable entries, exam records, or attendance sessions.

---

## 8. Timetable

**Path:** Sidebar → Academic → Timetable

The timetable defines when and where each course is taught, by which teacher, for which academic year.

### Creating a Timetable Entry

1. Click **New Timetable**
2. Fill in:
   - Course (required)
   - Teacher (required)
   - Academic Year
   - Day of Week: Monday through Saturday
   - Start Time, End Time
   - Room / Hall number
   - Section (e.g. A, B)
   - Semester Number
   - Is Active toggle
   - Notes (optional)
3. Click **Save**

### Viewing the Timetable

The timetable table is sorted by day order (Mon → Sat) then by start time. Use filters to view:
- A specific day
- A specific course or teacher
- A specific semester

The **Today's Classes** dashboard widget automatically shows today's schedule.

---

## 9. Attendance

**Path:** Sidebar → Attendance → Attendance Sessions

Attendance is tracked in two steps: (1) create an Attendance Session, (2) mark records for each student in that session.

### Step 1: Create an Attendance Session

1. Click **New Attendance Session**
2. Fill in:
   - Course
   - Teacher
   - Session Date
   - Start Time, End Time
   - Academic Year
   - Session Type: Lecture, Lab, Tutorial, Extra Class
   - Topic Covered (optional)
3. Click **Save**

### Step 2: Mark Attendance

After creating the session, click **Mark Attendance** on that session row.

For each student, set their status:
- **Present** — attended
- **Absent** — did not attend
- **Late** — arrived late (counts as Present for eligibility)
- **Leave** — approved leave (does not count as absent for disciplinary purposes)
- **Excused** — excused absence

### Attendance Rules

- Minimum attendance to be eligible for exams: **75%** (configurable in Settings → Academic)
- Late arrivals count as **Present** for eligibility calculation
- The attendance report PDF shows each course's attendance percentage and eligibility status

### Generating Attendance Reports

Go to **Students** → find a student → click the **Chart Bar icon** → downloads a PDF attendance report for that student.

---

## 10. Examinations & Results

**Path:** Sidebar → Examinations → Exams

### Creating an Exam

1. Click **New Exam**
2. Fill in:
   - Exam Title (e.g. "CS-101 Mid Term — Fall 2024")
   - Course
   - Academic Program
   - Academic Year
   - Exam Type: Mid Term, Final, Quiz, Assignment, Lab, Practical, Internal, External
   - Exam Date
   - Total Marks (e.g. 50)
   - Passing Marks (e.g. 23)
   - Semester Number
   - Is Published toggle (controls student visibility)
   - Results Published toggle (enables Result Sheet PDF button)
3. Click **Save**

### Entering Results

After creating the exam, click **Enter Results** on the exam row. For each student:

| Field | Description |
|-------|-------------|
| Marks Obtained | Numeric score |
| Is Absent | Toggle if student was absent |
| Grade & Points | **Auto-calculated** (see grading scale below) |

> Grades and GPA points are calculated **automatically** based on the percentage scored. You do not need to enter grades manually.

### Grading Scale (Automatic)

| Percentage | Grade | GPA Points | Label |
|-----------|-------|-----------|-------|
| 85% and above | A | 4.00 | Excellent |
| 80–84% | A- | 3.67 | Very Good |
| 75–79% | B+ | 3.33 | Good Plus |
| 70–74% | B | 3.00 | Good |
| 65–69% | B- | 2.67 | Satisfactory |
| 61–64% | C+ | 2.33 | Average Plus |
| 57–60% | C | 2.00 | Average |
| 53–56% | C- | 1.67 | Below Average |
| 50–52% | D+ | 1.33 | Poor Plus |
| 45–49% | D | 1.00 | Poor |
| Below 45% | F | 0.00 | Fail |

### Generating Result Sheet PDF

On an exam row, click the **Chart Bar icon** (Result Sheet). This is only visible when **Results Published** is toggled ON.

The PDF shows:
- Exam metadata
- Statistics: appeared, passed, failed, highest marks, average, pass rate
- Full student results table with grades and pass/fail status
- Signature lines for teacher, examination controller, and principal

---

## 11. Fee Management

**Path:** Sidebar → Students & Admissions → Fee Structure / Fee Payments

### Fee Structure

Define the fee rules first:

1. Go to **Fee Structure** → **New Fee Structure**
2. Fill in:
   - Title (e.g. "BS-CS Semester 1 Tuition Fee 2024-25")
   - Fee Type: Tuition, Admission, Exam, Library, Sports, Transport, Hostel, Miscellaneous
   - Academic Program (or leave blank for all programs)
   - Academic Year
   - Semester Number (or leave blank for all semesters)
   - Amount (PKR)
   - Late Fine Per Day (PKR)
   - Due Date
   - Frequency (dropdown — Monthly, Semester, Annual, One-Time)
   - Is Mandatory toggle
3. Click **Save**

### Generating a Fee Challan (Payment Record)

1. Go to **Fee Payments** → **New Fee Payment**
2. Fill in:
   - Student
   - Fee Structure
   - Academic Year
   - Amount Due (auto-filled from fee structure)
   - Due Date
   - Payment Status: Pending, Paid, Partial, Overdue, Waived
   - Challan Number (auto-generated)
3. Click **Save**

To print the challan as PDF: click the **Document icon** on the payment row.

### Payment Status Flow

```
[Created] → Pending
[Due date passed, not paid] → Overdue  (automatic, runs daily at 01:00 AM)
[Partial amount received] → Partial
[Full amount received] → Paid
[Fee waived by admin] → Waived
```

### Fee Overdue Detection

Every night at 01:00 AM, the system automatically:
1. Finds all Pending and Partial payments whose due date has passed
2. Changes their status to **Overdue**
3. Sends a notification to all Admin users (visible in the bell icon)

To run this manually:
```bash
php artisan fees:check-overdue
```

---

## 12. Library

**Path:** Sidebar → Library → Books / Book Issues

### Adding a Book

1. Go to **Books** → **New Book**
2. Fill in:
   - Title (required)
   - ISBN
   - Author
   - Publisher, Publication Year, Edition
   - Language (dropdown — configurable)
   - Category (dropdown — configurable: Science, Islamic Studies, etc.)
   - Total Copies, Available Copies
   - Location (shelf/rack)
   - Status: Available, Issued, Reserved, Lost, Damaged
3. Click **Save**

### Issuing a Book

1. Go to **Book Issues** → **New Book Issue**
2. Fill in:
   - Book
   - Student (or Teacher)
   - Issue Date (defaults to today)
   - Due Date (return deadline)
   - Condition on Issue: Good, Fair, Poor, Damaged
3. Click **Save**

### Returning a Book

1. Find the Book Issue record → click **Edit**
2. Set:
   - Return Date (today's date)
   - Condition on Return
   - Fine Charged (if returned late)
3. Toggle **Is Returned** to ON
4. Save

### Overdue & Fine Calculation

Fine per day is set in **Settings → Library**. The system shows which books are overdue based on the due date and return status. The **Library Stats** dashboard widget shows: Total Books, Available Today, Issued Today.

---

## 13. LMS Portal

**Path:** Sidebar → LMS Portal

The Learning Management System allows teachers to share materials and assign work to students.

### Course Materials

Upload study materials (PDF, Word, PowerPoint):
1. Go to **Course Materials** → **New Material**
2. Fill in Title, Course, Teacher, Material Type, Week Number
3. Upload file or paste external URL
4. Toggle **Published** when ready for students

### Assignments

1. Go to **Assignments** → **New Assignment**
2. Fill in Title, Course, Teacher, Total Marks
3. Set Submission Deadline (date and time)
4. Set Submission Type (File, Online, Physical, Presentation)
5. Toggle **Allow Late Submission** if needed
6. Toggle **Published** when ready

---

## 14. News & Updates

**Path:** Sidebar → System → News & Updates

Publish news articles, college updates, and press releases.

1. Click **New News Article**
2. Fill in Title (slug auto-generates), Category, Publish Date
3. Add Featured Image (optional)
4. Write Excerpt (short summary) and Full Content (rich editor)
5. Toggle **Published** to make it visible
6. Toggle **Featured** to highlight it

---

## 15. Announcements

**Path:** Sidebar → LMS Portal → Announcements

Send announcements to specific audiences:

| Audience | Who sees it |
|----------|------------|
| Everyone | All users |
| Students Only | Student users |
| Teachers Only | Teacher users |
| Specific Department | That department only |

Set Priority: Normal, High, Urgent (controls display order and badge color).

---

## 16. Reports & PDF Documents

All PDF reports require the user to be logged in. They open in a new browser tab and download automatically.

### Available PDFs

| Report | How to Access | What It Shows |
|--------|--------------|---------------|
| **Student Transcript** | Students → row → Document icon | Full academic record: results, grades, CGPA, attendance summary |
| **Attendance Report** | Students → row → Chart icon | Course-wise attendance with eligibility status |
| **Exam Result Sheet** | Exams → row → Chart icon (only when Results Published = ON) | All student results for that exam with statistics |
| **Fee Challan** | Fee Payments → row → Document icon | Single challan for printing and payment |

### PDF Design

All PDFs use:
- Official JDCA letterhead with college name, address, contact details
- Document reference number and print date
- Verification note
- Signature lines for Principal, relevant officer, and student/teacher

---

## 17. System Settings

**Path:** Sidebar → System → Settings

Settings are organized in sections:

### College Information
| Setting | Description |
|---------|-------------|
| College Name | Full official name |
| Short Name | Abbreviation (e.g. JDCA) |
| Principal Name | Current principal |
| Phone | Contact number |
| Email | Official email |
| Website | College website URL |
| Address | Physical address |
| City | City and postal code |
| Affiliation | Affiliated university/board |
| Accreditation | Certifications/accreditations |
| **College Logo** | Upload PNG logo (shown in admin topbar). Recommended: transparent PNG, max 400×120px |

### Academic Settings
| Setting | Description |
|---------|-------------|
| Current Academic Year | Active academic year |
| Current Semester | Current semester number |
| Min Attendance % | Minimum attendance for exam eligibility (default: 75%) |
| Max Semester | Maximum semesters in longest program |

### Fee Settings
| Setting | Description |
|---------|-------------|
| Currency | PKR |
| Late Fine Per Day | Default fine per day for overdue fees |
| Grace Period (Days) | Days after due date before overdue status |

### Library Settings
| Setting | Description |
|---------|-------------|
| Max Books Per Student | Borrowing limit |
| Loan Period (Days) | Default return period |
| Fine Per Day | Library overdue fine |

### Lookup Values (Dynamic Dropdowns)

**Path:** Sidebar → System → Lookup Values

These are all the dropdown lists used throughout the system. You can add, edit, or reorder options:

| Category | Used In |
|----------|---------|
| province | Student form, Teacher form |
| previous_qualification | Student form |
| highest_qualification | Teacher form |
| designation | Teacher form |
| book_language | Book form |
| book_category | Book form, filter |
| condition | Book Issue form |
| fee_frequency | Fee Structure form |
| lms_material_type | Course Material form |
| lms_submission_type | Assignment form |
| news_category | News article form |
| priority_level | Announcement form |

To add a new option:
1. Go to **Lookup Values** → **New Lookup Value**
2. Select the **Category** (which dropdown it belongs to)
3. Enter the **Value** (code, e.g. `kpk`) and **Label** (display text, e.g. `Khyber Pakhtunkhwa`)
4. Set **Sort Order** (lower = shown first)
5. Toggle **Active**
6. Click **Save**

---

## 18. User Management & Roles

**Path:** Sidebar → (Filament Shield) → Roles / Users

### Roles

| Role | Access Level |
|------|-------------|
| **super_admin** | Full access to everything including role management |
| **Developer** | Full access (for technical staff) |
| **panel_user** | Limited access — cannot manage users or settings |

### Creating a New Admin User

1. Go to the Users section (or via Laravel Telescope/Artisan)
2. Create user with name, email, and password
3. Assign role: `super_admin`, `Developer`, or `panel_user`

Using Artisan (IT Admin):
```bash
php artisan tinker
# Then inside tinker:
$user = \App\Models\User::create(['name'=>'Name','email'=>'email@example.com','password'=>bcrypt('password')]);
$user->assignRole('panel_user');
```

---

## 19. Artisan Commands (IT Admin)

All commands must be run from the project directory (`d:\Dashboard-J` locally) in a terminal or command prompt.

```bash
# Check and mark overdue fee payments, notify admins
php artisan fees:check-overdue

# Run any pending database migrations
php artisan migrate

# Run all seeders (re-seeds lookup values, settings, etc.)
php artisan db:seed

# Re-apply college settings from code (updates college name, address, etc.)
php artisan db:seed --class=CollegeSettingSeeder

# Reset dropdown options to defaults
php artisan db:seed --class=ListItemSeeder

# Rebuild Filament component cache (run after code changes)
php artisan filament:cache-components

# Clear compiled PDF view cache (run after changing PDF blade templates)
php artisan view:clear

# Clear route cache (run after adding/changing routes)
php artisan route:clear

# Clear application cache (also clears CollegeSetting cache)
php artisan cache:clear

# Create storage symlink (required ONCE after fresh install so uploaded files are accessible)
php artisan storage:link

# Start background queue worker (required for email/notification jobs if using queues)
php artisan queue:work

# Sync Filament Shield permissions (run after adding new resources)
php artisan shield:generate --all
```

---

## 20. Scheduled Tasks & Automation

The system runs automatic tasks using Laravel's built-in scheduler.

| Time | Command | What It Does |
|------|---------|-------------|
| Daily at **01:00 AM** | `fees:check-overdue` | Marks unpaid/partial fees past due date as Overdue. Sends bell notification to all super_admin and Developer users. |

### Enabling the Scheduler (Production Server)

For the scheduler to run automatically on a Linux server, add this cron job:
```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

On **XAMPP (Windows, local)**, the scheduler does not run automatically. Run manually:
```bash
php artisan schedule:run
# Or run the specific command:
php artisan fees:check-overdue
```

---

## 21. Troubleshooting & FAQs

### Q: Logo uploaded in Settings but not showing in topbar
**A:** Run `php artisan storage:link` once. Then go to Settings, re-save the logo.

### Q: PDF download gives a blank page or error
**A:** Run `php artisan view:clear` then try again. If still failing, check the PHP error log in `storage/logs/laravel.log`.

### Q: Dropdown options are empty (province, designation, etc.)
**A:** Run `php artisan db:seed --class=ListItemSeeder` to restore default dropdown values.

### Q: "No notifications showing" in the bell icon
**A:** Run `php artisan migrate` to ensure the `notifications` table exists. Then run `php artisan fees:check-overdue` to generate a test notification.

### Q: Grade not auto-calculating when entering exam results
**A:** The `marks_obtained` field must be filled. Grades calculate automatically on save based on the percentage: `(marks_obtained / total_marks) × 100`.

### Q: Attendance report shows 0% for a student
**A:** No attendance sessions have been created for that student's courses, or no records were marked. Create a session first, then mark attendance.

### Q: Can I add new dropdown options (e.g. a new province)?
**A:** Yes — go to **Settings → Lookup Values** → **New Lookup Value** → select category `province`, enter value and label.

### Q: How to change the passing marks threshold?
**A:** Each exam has its own **Passing Marks** field. Set it when creating or editing the exam.

### Q: How to reset all settings to JDCA defaults?
**A:** Run `php artisan db:seed --class=CollegeSettingSeeder`.

### Q: Multiple students have the same roll number
**A:** Roll numbers are auto-generated as `DEPT-YEAR-SEQUENCE`. If duplicates exist, manually edit one student's roll number to make it unique.

---

## 22. Business Logic Reference

### Roll Number Format
Auto-generated as: `DEPTCODE-YEAR-SEQUENCE`  
Example: `CS-2024-0001`  
Format: Department code (uppercase) + Batch year + 4-digit sequence number

### Grading Formula
```
percentage = (marks_obtained / total_marks) × 100
grade      = lookup from grading scale table (Section 10)
grade_points = from grading scale (0.00 to 4.00)
cgpa       = average of all grade_points across all exams
```

### CGPA to Percentage Conversion
```
percentage = cgpa × 25
Example: CGPA 3.50 → 87.5%
```

### Attendance Eligibility Rule
```
effective_attendance = present + late
percentage = (effective_attendance / total_sessions) × 100
eligible = percentage >= min_attendance_percent (default 75%)
```
- **Late** counts as Present for eligibility
- **Leave** and **Excused** are NOT counted as absent
- Minimum percentage is configurable in Settings → Academic

### Fee Overdue Logic
```
IF due_date < today AND status IN (pending, partial):
    status → overdue
    notify all super_admin and Developer users
```
This runs automatically at 01:00 AM daily, or can be triggered manually with `php artisan fees:check-overdue`.

### Fee Net Amount
```
net_amount = amount_due + late_fine - discount_amount
```
Late fine is calculated as: `fine_per_day × days_overdue`

### Library Overdue
```
overdue = is_returned = false AND due_date < today
fine = fine_per_day × (today - due_date).days
```
Fine per day is set in Settings → Library.

### Book Availability
```
available_copies = total_copies - currently_issued_count
A book can be issued only if available_copies > 0
```

### Scholarship Eligibility (Reference)
Scholarships are linked to students and may have eligibility criteria based on:
- CGPA (academic performance)
- Financial need
- Semester standing
Check the Scholarships module for specific criteria per scholarship.

---

*This guide covers the JDCA College Management System as of June 2026.*  
*For technical support, contact the system developer.*  
*College contact: jinnahschooldegreecollege@gmail.com | +923129776585*
