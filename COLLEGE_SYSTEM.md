# Complete College Management System with LMS
## Pakistan Degree College — Full Stack Web Application

> Built on Laravel 12 + Filament 3 Admin Panel  
> HEC Pakistan Compliant | Fully Dynamic | Role-Based Access Control

---

## Table of Contents
1. [System Overview](#1-system-overview)
2. [Theme & Design Recommendation](#2-theme--design-recommendation)
3. [User Roles & Permissions](#3-user-roles--permissions)
4. [Admin Dashboard Modules](#4-admin-dashboard-modules)
5. [LMS Modules](#5-lms-modules)
6. [Public Website Pages (Dynamic CMS)](#6-public-website-pages-dynamic-cms)
7. [HEC Pakistan Grading System](#7-hec-pakistan-grading-system)
8. [Database Tables Overview](#8-database-tables-overview)
9. [Filament Resources List](#9-filament-resources-list)
10. [API Endpoints](#10-api-endpoints)

---

## 1. System Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                COLLEGE MANAGEMENT SYSTEM (CMS+LMS)              │
├───────────────────┬─────────────────────┬───────────────────────┤
│   PUBLIC WEBSITE  │   ADMIN DASHBOARD   │     STUDENT/LMS       │
│   (Dynamic CMS)   │   (Filament Panel)  │      PORTAL           │
├───────────────────┼─────────────────────┼───────────────────────┤
│ • Home            │ • Super Admin       │ • Student Dashboard   │
│ • About Us        │ • Admin             │ • My Courses          │
│ • Academics       │ • Web Developer     │ • Assignments         │
│ • Departments     │ • Teachers Panel    │ • Exam Results        │
│ • Admission       │ • Library Mgmt      │ • Attendance          │
│ • LMS Portal      │ • Accounts          │ • Fee Challan         │
│ • Scholarships    │ • HR Module         │ • Library Card        │
│ • Alumni          │ • Reports           │ • Parent Portal       │
│ • Contact Us      │ • Settings          │ • Teacher Portal      │
└───────────────────┴─────────────────────┴───────────────────────┘
```

---

## 2. Theme & Design Recommendation

### Recommended: Modern Flat + Card-Based Design

**Color Palette (Professional Pakistani College):**
```
Primary:    #1B3A6B   (Deep Navy Blue  — trust, authority, academia)
Secondary:  #2ECC71   (Emerald Green   — Islam, Pakistan, growth)
Accent:     #E74C3C   (Red             — Pakistan flag accent)
Light:      #F8F9FA   (Off White       — clean background)
Dark:       #2C3E50   (Charcoal        — text)
Gold:       #F39C12   (Gold            — achievements, medals)
```

**Typography:**
```
Headings:  Poppins (Bold 700)      — modern, professional
Body:      Inter (Regular 400)     — readable, clean
Urdu:      Noto Nastaliq Urdu      — for Urdu content support
```

**Design Principles:**
- Mobile-first responsive design
- Hero section with college building full-width image
- Floating navigation with sticky header on scroll
- Card-based departments and faculty listings
- Parallax sections for key statistics (Students, Faculty, Departments)
- Counter animations (Total Students, Total Faculty, Years of Excellence)
- WhatsApp floating button for quick contact
- Breadcrumb navigation on all inner pages
- Dark mode toggle
- RTL support for Urdu pages

**Best Reference Designs:**
- LUMS website (lums.edu.pk)
- FAST-NUCES (nu.edu.pk)
- University of Punjab (pu.edu.pk)

---

## 3. User Roles & Permissions

### 3.1 Role Hierarchy

```
Super Admin
    └── Admin
          ├── Web Developer
          ├── Accounts Officer
          ├── Admission Officer
          ├── Teacher / Lecturer
          │     └── Class Teacher
          ├── Librarian
          ├── Student
          │     └── Class Representative (CR)
          └── Parent / Guardian
```

### 3.2 Detailed Role Permissions

#### 🔴 Super Admin
| Module | Create | Read | Update | Delete | Manage |
|--------|--------|------|--------|--------|--------|
| All Modules | ✅ | ✅ | ✅ | ✅ | ✅ |
| System Settings | ✅ | ✅ | ✅ | ✅ | ✅ |
| User Management | ✅ | ✅ | ✅ | ✅ | ✅ |
| Role Management | ✅ | ✅ | ✅ | ✅ | ✅ |
| Logs & Audit Trail | ❌ | ✅ | ❌ | ✅ | ✅ |
| Database Backup | ✅ | ✅ | ✅ | ✅ | ✅ |

#### 🟠 Admin (Principal / College Admin)
| Module | Create | Read | Update | Delete |
|--------|--------|------|--------|--------|
| Students | ✅ | ✅ | ✅ | ✅ |
| Teachers | ✅ | ✅ | ✅ | ✅ |
| Departments | ✅ | ✅ | ✅ | ✅ |
| Courses | ✅ | ✅ | ✅ | ✅ |
| Admissions | ✅ | ✅ | ✅ | ✅ |
| Fee Management | ✅ | ✅ | ✅ | ✅ |
| Exam / Results | ✅ | ✅ | ✅ | ✅ |
| Reports | ✅ | ✅ | ❌ | ❌ |
| Scholarships | ✅ | ✅ | ✅ | ✅ |
| Library | ✅ | ✅ | ✅ | ❌ |
| Website CMS | ✅ | ✅ | ✅ | ✅ |
| Notices / Announcements | ✅ | ✅ | ✅ | ✅ |

#### 🟡 Web Developer
| Module | Create | Read | Update | Delete |
|--------|--------|------|--------|--------|
| Website Pages (CMS) | ✅ | ✅ | ✅ | ✅ |
| Menus | ✅ | ✅ | ✅ | ✅ |
| Sliders / Banners | ✅ | ✅ | ✅ | ✅ |
| Gallery | ✅ | ✅ | ✅ | ✅ |
| News & Events | ✅ | ✅ | ✅ | ✅ |
| Downloads | ✅ | ✅ | ✅ | ✅ |
| SEO Settings | ✅ | ✅ | ✅ | ❌ |
| System Settings | ❌ | ✅ | ❌ | ❌ |

#### 🟢 Teacher / Lecturer
| Module | Create | Read | Update | Delete |
|--------|--------|------|--------|--------|
| My Courses | ❌ | ✅ | ✅ | ❌ |
| Course Content (LMS) | ✅ | ✅ | ✅ | ✅ |
| Assignments | ✅ | ✅ | ✅ | ✅ |
| Quizzes | ✅ | ✅ | ✅ | ✅ |
| Attendance | ✅ | ✅ | ✅ | ❌ |
| Marks / Grades | ✅ | ✅ | ✅ | ❌ |
| My Students | ❌ | ✅ | ❌ | ❌ |
| Announcements | ✅ | ✅ | ✅ | ✅ |
| Discussion Forum | ✅ | ✅ | ✅ | ✅ |
| Lesson Plans | ✅ | ✅ | ✅ | ✅ |

#### 🔵 Student
| Module | Create | Read | Update | Delete |
|--------|--------|------|--------|--------|
| My Profile | ❌ | ✅ | ✅ (limited) | ❌ |
| My Courses | ❌ | ✅ | ❌ | ❌ |
| Course Content | ❌ | ✅ | ❌ | ❌ |
| Assignments | ✅ (submit) | ✅ | ✅ (own) | ❌ |
| Quiz Attempt | ✅ | ✅ | ❌ | ❌ |
| Attendance | ❌ | ✅ (own) | ❌ | ❌ |
| Results / Grades | ❌ | ✅ (own) | ❌ | ❌ |
| Fee Challan | ❌ | ✅ | ❌ | ❌ |
| Library (borrow/return) | ✅ | ✅ | ❌ | ❌ |
| Discussion Forum | ✅ | ✅ | ✅ (own) | ✅ (own) |
| Online Admission | ✅ | ✅ | ✅ | ❌ |

#### 🟣 Parent / Guardian
| Module | Read |
|--------|------|
| Child Attendance | ✅ |
| Child Results / Grades | ✅ |
| Fee Status | ✅ |
| Notices & Announcements | ✅ |
| Teacher Contact | ✅ |
| Child Progress Report | ✅ |

#### 📚 Librarian
| Module | Create | Read | Update | Delete |
|--------|--------|------|--------|--------|
| Books Catalog | ✅ | ✅ | ✅ | ✅ |
| Issue / Return | ✅ | ✅ | ✅ | ❌ |
| Fine Management | ✅ | ✅ | ✅ | ❌ |
| Student Library Cards | ✅ | ✅ | ✅ | ❌ |
| E-Books / Resources | ✅ | ✅ | ✅ | ✅ |
| Reports | ❌ | ✅ | ❌ | ❌ |

---

## 4. Admin Dashboard Modules

### 4.1 Dashboard Overview (Home)
- Total Students (with graph)
- Total Teachers
- Total Active Courses
- Fee Collection This Month
- Pending Admissions
- Attendance Today (%)
- Recent Activity Feed
- Quick Actions: Add Student, Add Teacher, Generate Result, Send Notice
- Upcoming Events Calendar
- Latest News Ticker

### 4.2 Student Management
```
Students
├── All Students List (searchable, filterable by department/semester/batch)
├── Add New Student
├── Student Profile
│     ├── Personal Information
│     ├── Academic Record
│     ├── Attendance History
│     ├── Fee History
│     ├── Results / Transcript
│     ├── Library Record
│     └── Documents (CNIC, Photo, etc.)
├── Student ID Card Generator
├── Bulk Import (Excel/CSV)
├── Bulk Export
├── Promotion (Semester/Year)
└── Alumni Convert
```

**Student Fields:**
- Roll Number (auto-generated)
- Registration Number (HEC format)
- Full Name (in English & Urdu)
- Father Name
- CNIC / B-Form Number
- Date of Birth
- Gender
- Religion
- Domicile District
- Permanent Address
- Current Address
- Phone Number
- Father Phone
- Emergency Contact
- Blood Group
- Department
- Program (BS, B.Ed, M.Ed, etc.)
- Semester
- Batch/Year
- Admission Date
- Student Status (Active / Suspended / Alumni / Left)
- Photo
- Category (Regular / Ex-Student / Self-Finance)
- Scholarship Status

### 4.3 Teacher / Faculty Management
```
Faculty
├── All Faculty List
├── Add New Faculty
├── Faculty Profile
│     ├── Personal Information
│     ├── Academic Qualifications
│     ├── Teaching Experience
│     ├── Assigned Courses
│     ├── Attendance
│     ├── Salary Record
│     └── Documents
├── Faculty Directory (public-facing)
├── Designation Management
└── Leave Management
```

**Teacher Designations:**
- Vice Chancellor / Principal
- Director
- Associate Professor
- Assistant Professor
- Lecturer
- Lab Instructor
- Visiting Faculty
- Demonstrator

### 4.4 Department Management
```
Departments
├── Department of Education
├── Department of Physical Education
├── Department of Sociology
├── Department of Computer Science
├── Department of English
├── Department of Continuous Education
├── Add New Department
└── Department Settings
      ├── Department Name (EN + UR)
      ├── Department Head
      ├── Message from Director/HOD
      ├── Description
      ├── Programs Offered
      ├── Faculty List
      ├── Gallery
      └── Downloads
```

### 4.5 Course / Subject Management
```
Courses
├── All Courses
├── Add Course
│     ├── Course Code (e.g., CS-301)
│     ├── Course Title
│     ├── Credit Hours
│     ├── Department
│     ├── Semester
│     ├── Program
│     ├── Course Type (Theory/Lab/Both)
│     ├── Assigned Teacher
│     └── Timetable Slot
├── Timetable Generator
├── Course Enrollment Management
└── Syllabus Upload
```

### 4.6 Admission Management
```
Admissions
├── Online Applications
│     ├── Pending Review
│     ├── Shortlisted
│     ├── Approved
│     ├── Rejected
│     └── Waitlist
├── Admission Form Builder
├── Merit List Generator (Auto)
├── Admission Criteria / Policy
├── Program-wise Seats Management
├── Documents Checklist
├── Admission Notifications (Email/SMS)
├── Fee Structure (per program per semester)
└── Reports
```

**Admission Programs (Pakistan Degree College):**
- B.S. Education (4 Years)
- B.Ed. (1.5 Years)
- M.Ed. (1.5 Years)
- B.S. Physical Education
- B.S. Computer Science
- B.S. English
- B.S. Sociology
- Short Courses / Continuous Education

### 4.7 Fee Management
```
Fees
├── Fee Structure
│     ├── Program-wise Fee
│     ├── Semester Fee
│     ├── Hostel Fee
│     └── Lab/Library/Sports Fee
├── Fee Challan Generator
├── Fee Collection
├── Pending Fee Reports
├── Scholarship Deductions
├── Fine Management
├── Bank Reconciliation
└── Monthly/Annual Fee Reports
```

**Fee Categories:**
- Tuition Fee
- Admission Fee (one-time)
- Registration Fee
- Examination Fee
- Library Fee
- Sports Fee
- Lab Fee
- Student Union Fee
- Red Crescent Fee
- Security Deposit (refundable)

### 4.8 Attendance Management
```
Attendance
├── Daily Attendance (by teacher per subject)
├── Student Attendance Summary
├── Attendance Reports (weekly/monthly)
├── Low Attendance Alerts (below 75% — HEC rule)
├── Leave Applications
│     ├── Student Leave
│     └── Teacher Leave
└── Attendance Calendar
```

### 4.9 Examination & Results
```
Examinations
├── Exam Schedule
│     ├── Mid Semester Exam
│     └── Final Exam
├── Exam Seating Plan
├── Marks Entry
│     ├── Mid Term Marks
│     ├── Final Term Marks
│     ├── Sessional Marks (Assignments + Quiz + Attendance)
│     └── Lab Marks
├── Result Processing
│     ├── GPA Calculation (HEC Formula)
│     ├── CGPA Calculation
│     ├── Grade Assignment
│     └── Pass/Fail Determination
├── Result Cards / DMC Generator (PDF)
├── Transcript Generator (PDF)
├── Result Publication
└── Rechecking / Appeal Module
```

### 4.10 Scholarship Management
```
Scholarships
├── Merit-Based Scholarship
│     └── Top students per department
├── Need-Based Scholarship
│     └── Based on family income
├── Orphan Scholarship
│     └── Death certificate required
├── Special Category
│     ├── Sports Scholarship
│     ├── Disabled Students
│     └── Custom Category
├── Application Forms
├── Review & Approval Workflow
├── Payment Schedule
└── Reports
```

### 4.11 Library Management
```
Library
├── Books Catalog
│     ├── Add Book (ISBN, Title, Author, Publisher, Edition, Copies)
│     ├── Categories / Subjects
│     ├── Search Books
│     └── E-Books / Digital Resources
├── Book Issue / Return
├── Student Library Cards
├── Fine Calculation (per day)
├── Reservation System
├── Library Reports
└── Inventory Management
```

### 4.12 Hostel Management (Optional)
```
Hostel
├── Rooms Management
├── Student Allotment
├── Hostel Fee
├── Attendance
├── Warden Management
└── Mess Management
```

### 4.13 HR & Payroll
```
HR Module
├── Staff Records
├── Attendance (Biometric Integration)
├── Leave Management
├── Salary Management
│     ├── Basic Pay
│     ├── Allowances
│     └── Deductions (EOBI, Income Tax)
├── Increment Management
├── Performance Evaluation
└── Payslip Generator
```

### 4.14 Announcements & Communication
```
Communication
├── Notices
│     ├── Admin Notice
│     ├── Exam Notice
│     └── General Notice
├── News & Events
├── SMS Gateway Integration
├── Email Notifications
├── WhatsApp Notifications (optional)
├── Notice Board (digital)
└── Circular Management
```

### 4.15 Reports & Analytics
```
Reports
├── Student Reports
│     ├── Enrollment Report
│     ├── Attendance Report
│     ├── Result Report
│     └── Fee Report
├── Teacher Reports
│     ├── Faculty List
│     └── Teaching Load
├── Financial Reports
│     ├── Fee Collection
│     ├── Scholarship Payments
│     └── Budget Reports
├── Academic Reports
│     ├── Pass/Fail Statistics
│     ├── CGPA Distribution
│     └── Department-wise Performance
└── Custom Report Builder
```

### 4.16 System Settings
```
Settings
├── College Information
│     ├── College Name (EN + UR)
│     ├── Logo & Favicon
│     ├── Address & Contact
│     ├── Social Media Links
│     ├── Google Maps Embed
│     └── Email/SMS Configuration
├── Academic Settings
│     ├── Current Semester
│     ├── Academic Year
│     ├── Grading System
│     └── Attendance Rules (75% default)
├── Website Settings
│     ├── Theme Color
│     ├── Home Page Sections Enable/Disable
│     ├── Maintenance Mode
│     └── SEO (Meta Title, Description)
├── User Management
│     ├── All Users
│     ├── Roles & Permissions
│     └── Activity Logs
├── Email Templates
├── SMS Templates
├── Backup & Restore
└── Audit Trail
```

---

## 5. LMS Modules

### 5.1 LMS Overview
```
College LMS Portal
├── Student Portal
│     ├── My Dashboard
│     ├── My Enrolled Courses
│     ├── Course Content (Video/PDF/Slides)
│     ├── Assignments
│     ├── Online Quizzes
│     ├── Discussion Forum
│     ├── My Results
│     ├── My Attendance
│     ├── Fee Status
│     └── Library Access
├── Teacher Portal
│     ├── My Courses
│     ├── Upload Content
│     ├── Manage Assignments
│     ├── Create Quizzes
│     ├── Mark Attendance
│     ├── Enter Marks
│     ├── Discussion Moderation
│     └── Announcements
└── Parent Portal
      ├── Child Progress
      ├── Attendance Summary
      ├── Results
      └── Fee Status
```

### 5.2 Course Content Types
- **Video Lectures** (YouTube embed or direct upload)
- **PDF Notes / Slides** (PowerPoint converted)
- **Recorded Classes** (Zoom / Google Meet recordings)
- **Text Articles**
- **External Links** (HEC Digital Library, etc.)
- **Audio Lectures**
- **Interactive Presentations**

### 5.3 Assignment Module
```
Assignment
├── Title & Instructions
├── Marks Allocation
├── Due Date & Time
├── File Upload (PDF, DOC, ZIP)
├── Plagiarism Check (basic)
├── Submission Status
├── Teacher Feedback & Grading
├── Late Submission Penalty
└── Turnitin Integration (optional)
```

### 5.4 Quiz / Online Exam Module
```
Quiz System
├── Question Bank
│     ├── MCQ (Single/Multiple correct)
│     ├── True/False
│     ├── Short Answer
│     └── Fill in the Blank
├── Quiz Settings
│     ├── Time Limit
│     ├── Attempts Allowed
│     ├── Shuffle Questions
│     ├── Shuffle Options
│     ├── Show Result Immediately
│     └── Pass Marks
├── Auto Grading
├── Review Mode (after attempt)
└── Anti-Cheating
      ├── Full Screen Mode
      ├── Tab Switch Detection
      └── Time per Question
```

### 5.5 Discussion Forum
```
Forum
├── Course-wise Forums
├── General Discussion
├── Q&A Section
├── Teacher moderation
├── Like / Reply
└── Best Answer Marking
```

---

## 6. Public Website Pages (Dynamic CMS)

> All pages are managed from Admin Panel → Website Management

### 6.1 Navigation Menu (Dynamic)
```
Main Navigation
├── Home
├── About Us
│     ├── History & Geography
│     ├── Mission & Vision
│     ├── Message from VC / Principal
│     └── Campus Facilities
├── Academics
│     ├── Departments
│     │     ├── Department of Education
│     │     │     ├── Message from Director
│     │     │     ├── Faculty Profile
│     │     │     └── Downloads
│     │     ├── Department of Physical Education
│     │     │     ├── Message from Principal
│     │     │     ├── Faculty Profile
│     │     │     └── Downloads
│     │     ├── Department of Sociology
│     │     ├── Department of Computer Science
│     │     ├── Department of English
│     │     └── Department of Continuous Education
│     ├── Programs Offered
│     ├── Semester Rules
│     └── College Gallery
├── Admission
│     ├── Admission Procedure
│     ├── Online Admission Form
│     ├── Merit List (Published)
│     └── Fee Structure
├── College LMS
│     └── (Redirects to LMS Login)
├── Scholarships
│     ├── Merit-Based Scholarship
│     ├── Need-Based Scholarship
│     ├── Orphan Scholarship
│     └── Special Category
├── Alumni
├── Contact Us
└── [Dynamic Custom Pages]
```

### 6.2 Page Types (CMS)

#### Home Page Sections (all toggle-able from admin):
- **Hero Slider** — full-width with college images, title, button
- **Stats Counter** — Students, Faculty, Departments, Years
- **About Us Preview** — short intro + read more
- **Programs Offered** — card grid
- **News & Events** — latest 3
- **Faculty Spotlight** — random featured teachers
- **Gallery Preview** — latest photos
- **Testimonials** — student/alumni quotes
- **Admission CTA Banner** — apply now button
- **Partners / Affiliations** — HEC, Punjab Govt, etc.
- **Contact Strip** — address, phone, email, map

#### About Us Page:
- Rich text editor (WYSIWYG)
- Image upload
- Timeline for History
- Mission/Vision separate sections
- Principal/VC message with photo

#### Department Pages (each department):
- Department banner image
- About / Introduction
- Message from HOD (photo + text)
- Faculty Profiles (cards with photo, designation, qualifications)
- Programs offered
- Course list
- Downloads (PDF forms, timetables, results)
- Gallery
- Contact

#### Admission Pages:
- Admission procedure (step-by-step)
- Online Admission Form (dynamic form builder)
- Merit list table (published from admin)
- Fee structure table (program-wise)
- Required documents checklist

#### Scholarship Pages:
- Scholarship types
- Eligibility criteria
- Application process
- Deadline
- Online application form

#### Gallery Page:
- Album-based gallery
- Image + Video support
- Year-wise filter
- Event-wise filter

#### Downloads Page:
- Categorized file downloads
- PDF, DOC, Excel
- Date added, file size shown

#### Contact Us Page:
- Contact form (name, email, subject, message)
- Google Maps embed (dynamic from settings)
- Address, Phone, Email from settings
- Social media links
- Office hours

### 6.3 CMS Features (Web Developer / Admin)
```
Website Management
├── Pages
│     ├── All Pages List
│     ├── Create New Page
│     ├── Edit Page (WYSIWYG + Blocks)
│     ├── Enable / Disable Page
│     ├── SEO Settings per page
│     └── Page Slug / URL
├── Menus
│     ├── Main Navigation Builder (drag & drop)
│     ├── Footer Menu
│     └── Quick Links Menu
├── Hero Sliders
│     ├── Add / Edit Slide
│     ├── Enable / Disable
│     └── Order Management
├── News & Events
│     ├── Add News
│     ├── Add Event (with date, venue)
│     ├── Categories
│     └── Publish / Draft
├── Gallery
│     ├── Albums
│     ├── Upload Photos
│     └── Video Gallery
├── Downloads
│     ├── Upload Files
│     ├── Categories
│     └── Enable / Disable
├── Testimonials
├── Pop-up Notices
│     ├── Enable / Disable
│     ├── Start Date / End Date
│     └── Content (image or text)
└── SEO Settings
      ├── Global Meta Title & Description
      ├── Open Graph Settings
      └── Google Analytics / Tag Manager
```

---

## 7. HEC Pakistan Grading System

### 7.1 Letter Grade Scale (HEC Standard)

| Letter Grade | Grade Points | Percentage Range | Description |
|-------------|-------------|-----------------|-------------|
| A | 4.00 | 85% – 100% | Excellent |
| A- | 3.67 | 80% – 84% | Very Good |
| B+ | 3.33 | 75% – 79% | Good Plus |
| B | 3.00 | 70% – 74% | Good |
| B- | 2.67 | 65% – 69% | Satisfactory |
| C+ | 2.33 | 61% – 64% | Average Plus |
| C | 2.00 | 57% – 60% | Average (Minimum Pass) |
| C- | 1.67 | 53% – 56% | Below Average |
| D+ | 1.33 | 50% – 52% | Poor Plus |
| D | 1.00 | 45% – 49% | Poor |
| F | 0.00 | Below 45% | Fail |
| W | — | — | Withdrawn |
| I | — | — | Incomplete |

### 7.2 GPA Calculation Formula
```
GPA  = Σ (Grade Points × Credit Hours) / Σ Credit Hours
CGPA = Σ All Semesters Grade Points / Σ All Credit Hours

HEC Percentage Formula: Percentage = CGPA × 25

Minimum CGPA for promotion:    2.00
Minimum CGPA for graduation:   2.00
Maximum CGPA:                  4.00

Division:
• First Division:  CGPA 3.00 – 4.00  (75% – 100%)
• Second Division: CGPA 2.00 – 2.99  (50% – 74%)
• Fail:            CGPA below 2.00
```

### 7.3 Marks Distribution (HEC Guidelines)
```
Theory Course (3 Credit Hours):
├── Mid Semester Exam:    30 marks
├── Final Exam:           50 marks
└── Sessional (Total):    20 marks
      ├── Assignments:    10 marks
      ├── Quiz:            5 marks
      └── Attendance:      5 marks

Lab Course (1 Credit Hour):
├── Lab Performance:      60 marks
└── Lab Report:           40 marks

Total per Course: 100 marks
```

### 7.4 Attendance Rules (HEC Mandatory)
- Minimum 75% attendance required per course
- Below 75% → student cannot appear in final exam
- 3 warnings system: 60%, 70%, 75%
- Leave of absence must be approved by principal

### 7.5 Semester Duration
```
Semester Duration: 16–18 weeks
├── Week 1–7:    Teaching
├── Week 8:      Mid Semester Exam
├── Week 9–15:   Teaching continues
└── Week 16–18:  Final Exam

Semesters per year: 2 (Fall + Spring)
Summer semester: Optional (repeat/improvement)
```

---

## 8. Database Tables Overview

```sql
-- USERS & AUTH
users, roles, permissions, model_has_roles, model_has_permissions, role_has_permissions

-- COLLEGE SETUP
colleges, departments, programs, semesters, academic_years, batches

-- PEOPLE
students, teachers, staff, parents, alumni

-- ACADEMIC
courses, course_enrollments, timetables, syllabus

-- ATTENDANCE
attendances, leave_applications, leave_types

-- EXAMINATION
exams, exam_schedules, marks, results, grade_reports, transcripts

-- FEE
fee_structures, fee_challans, fee_payments, scholarships, scholarship_applications

-- LMS
lms_course_content, assignments, assignment_submissions, quizzes, quiz_questions,
quiz_attempts, quiz_answers, discussion_forums, forum_posts, forum_replies

-- LIBRARY
books, book_categories, book_issues, library_cards, book_fines

-- WEBSITE CMS
pages, menus, menu_items, sliders, news, events, gallery_albums, gallery_images,
downloads, testimonials, popups, seo_settings

-- SYSTEM
settings, activity_logs, notifications, announcements, email_templates, sms_logs
```

---

## 9. Filament Resources List

```
php artisan make:filament-resource Student --generate
php artisan make:filament-resource Teacher --generate
php artisan make:filament-resource Department --generate
php artisan make:filament-resource Program --generate
php artisan make:filament-resource Course --generate
php artisan make:filament-resource Semester --generate
php artisan make:filament-resource Admission --generate
php artisan make:filament-resource FeeStructure --generate
php artisan make:filament-resource FeChallan --generate
php artisan make:filament-resource Scholarship --generate
php artisan make:filament-resource Attendance --generate
php artisan make:filament-resource Exam --generate
php artisan make:filament-resource Result --generate
php artisan make:filament-resource Book --generate
php artisan make:filament-resource BookIssue --generate
php artisan make:filament-resource Page --generate
php artisan make:filament-resource Slider --generate
php artisan make:filament-resource News --generate
php artisan make:filament-resource Gallery --generate
php artisan make:filament-resource Download --generate
php artisan make:filament-resource Announcement --generate
php artisan make:filament-resource Setting --generate
```

---

## 10. API Endpoints

### For Mobile App (Future)
```
POST   /api/auth/login
POST   /api/auth/logout
GET    /api/student/profile
GET    /api/student/courses
GET    /api/student/attendance
GET    /api/student/results
GET    /api/student/fee
GET    /api/student/assignments
POST   /api/student/assignment/submit
GET    /api/lms/course/{id}/content
GET    /api/teacher/my-courses
POST   /api/teacher/attendance/mark
POST   /api/teacher/marks/enter
GET    /api/notices
GET    /api/events
GET    /api/news
```

---

## Implementation Roadmap

### Phase 1 — Foundation (Week 1–2)
- [ ] Laravel + Filament setup ✅ (Done)
- [ ] Database migrations
- [ ] User roles & Filament Shield
- [ ] Basic settings module
- [ ] College info setup

### Phase 2 — Core Admin (Week 3–4)
- [ ] Student management
- [ ] Teacher management
- [ ] Department management
- [ ] Course management
- [ ] Admission module

### Phase 3 — Academic (Week 5–6)
- [ ] Attendance system
- [ ] Fee management
- [ ] Examination & results (HEC grading)
- [ ] Scholarship module

### Phase 4 — LMS (Week 7–9)
- [ ] LMS student portal
- [ ] Course content upload
- [ ] Assignments
- [ ] Online quizzes
- [ ] Discussion forum
- [ ] Parent portal

### Phase 5 — Public Website CMS (Week 10–12)
- [ ] All dynamic pages
- [ ] Navigation builder
- [ ] Sliders, news, events, gallery
- [ ] Online admission form
- [ ] Contact form

### Phase 6 — Library & HR (Week 13–14)
- [ ] Library management
- [ ] HR & payroll
- [ ] Reports & analytics

### Phase 7 — Polish (Week 15–16)
- [ ] Mobile responsive testing
- [ ] Performance optimization
- [ ] SEO setup
- [ ] Security audit
- [ ] Documentation

---

*Prepared for: Pakistani Degree College Management System*
*Stack: Laravel 12 + Filament 3 + MySQL + Tailwind CSS*
*HEC Compliant | Mobile Ready | Fully Dynamic*
