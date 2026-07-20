# Jinnah Degree College Astore — Complete System Guide (Deep)

_College ke admin/staff ke liye mukammal reference. Har module ke pages, har dropdown (options kahan se aate/add hote hain), Settings ka har field, home page ka har section, portals, links & passwords — sab. Future mein bhi kaam aayega._

---

## 🔑 Quick Access — Links & Logins

| Kya | Link | Login ID | Password |
|---|---|---|---|
| **Admin Panel** | `/admin` | `admin@admin.com` | `admin123` |
| **Student Portal** | `/portal/login` | Registration Number | initial `123456` |
| **Teacher Portal** | `/teacher/login` | Employee ID (JDCA-T-001) ya email | initial `123456` |
| **Public Website** | `/` | — | — |

> Site: `https://college-management-system-production-7fa1.up.railway.app`
> Passwords **initial** hain — user "Forgot password?" (email OTP) se ya admin **🔑 Set Portal Password** button se badal sakte hain.

## Dropdown options — 3 kism (padhne se pehle samajh lein)
Har dropdown ke options 3 mein se ek jagah se aate hain:
- **🟢 Lookup Values** — aap khud add/edit kar sakte hain: **System → Lookup Values**. (Foran dropdown mein aa jaata hai.)
- **🔵 Module records** — options doosre module ke records hain (jaise Department, Programme, Academic Year). Us module mein record banao → dropdown mein aa jayega.
- **🔴 Fixed (code)** — code mein tay-shuda list (enum). Ise badalne ke liye **developer** chahiye.

---

# PART 1 — Har Module: Pages + Functionality + Dropdowns

Har module ke: **pages** (list/add/edit/view), **filters**, **buttons/actions**, **form sections**, aur **uske dropdowns** (source ke saath).

## 🏛️ College Setup

### Departments
- **Pages:** List, Add, Edit. Table **drag se reorder** hoti hai.
- **Filters:** Type, Active, Website Visibility, Trashed (recycle bin).
- **Actions:** Edit · **Toggle Status** (Activate/Deactivate) · Delete/Restore.
- **Form sections:** Basic Info · Head of Department · Content (vision/mission/banner) · Contact & Display.
- **Dropdowns:** Department Type → **🔴 Fixed**.

### Academic Programs
- **Pages:** List, Add, Edit. Drag-reorder.
- **Filters:** Department, Degree Type, Admission Category, Active, Website Visibility, Trashed.
- **Actions:** Edit · Toggle Status · Delete/Restore.
- **Form sections:** Identity · Classification · Description · Display Settings.
- **Dropdowns:** Department → **🔵 Departments module** · Degree Type → **🔴 Fixed** · Admission Category → **🔴 Fixed**.

## 👨‍🎓 Students & Admissions

### Students
- **Pages:** List, Add, Edit.
- **List tabs (upar):** All · Unique · Duplicates · No Registration # · No Roll # (har ek pe count; aakhri teen highlight — reconcile karne ke liye).
- **Filters:** Department, Program, Status, Gender, Semester, Batch Year, Needs Review, Active, Hosteler, Trashed.
- **Row actions:** Edit · **Change Status** (naya status + wajah) · **🔑 Set Portal Password** · Delete/Restore.
- **Upar ke buttons:** **Export to Excel** · **Download Import Template** · **Import Students from Excel** · New.
- **Bulk (select kar ke):** **Generate Fee Challans** · **Download Fee Challans (1 PDF, max 200)** · Delete.
- **Form sections (tabs):** Personal Info · Contact & Address · Academic Info · Previous Education · Guardian & Remarks.
- **Dropdowns:** Gender → 🔴 · Blood Group → 🔴 · **Province → 🟢 Lookup** · Department → 🔵 · Academic Program → 🔵 (department se filtered) · Admission Academic Year → 🔵 · Current Semester → 🔴 (1–8) · Admission Type → 🔴 · Status → 🔴 · **Previous Qualification → 🟢 Lookup**.

### Admission Inquiries
- **Pages:** List, View (tafseeli), Edit. (Add nahi — public form se banti hain.)
- **Filters:** Status, Program.
- **Row actions:** **Enroll as Student** (student Create pre-filled khol deta hai) · View · Edit · Delete.
- **View mein:** status, personal info, academic background, marks table, notes.
- **Dropdowns:** Program Interested → 🔵 · Qualification → 🔴 (hardcoded) · Status → 🔴 (new/contacted/enrolled/rejected).
- Nav badge = nayi inquiries ka count.

## 👩‍🏫 Faculty & Staff

### Teachers
- **Pages:** List, Add, Edit.
- **Filters:** Department, Employment Type, Status, Designation, Active, Trashed.
- **Row actions:** Edit · **Change Status** (+wajah) · **🔑 Set Portal Password** · Delete/Restore.
- **Upar ke buttons:** Export · Download Teacher Template · Import Teachers · New.
- **Form tabs:** Personal · Contact · Qualification · Employment (details + Salary/Grade + Status).
- **Dropdowns:** Gender → 🔴 · Blood Group → 🔴 · **Province → 🟢 Lookup** · **Highest Degree → 🟢 Lookup (`teacher_qualification`)** · Department → 🔵 · **Designation → 🟢 Lookup (`teacher_designation`)** · Employment Type → 🔴 · BPS/Pay Scale → 🔴 (1–22) · Status → 🔴.

## 💰 Finance

### Fee Structure
- **Pages:** List, Add, Edit. **Filters:** Fee Type, Program, Active, Trashed.
- **Form:** Fee Details (title, type, semester, program, year, amount, late fine, due date, frequency, toggles).
- **Dropdowns:** Fee Type → 🔴 · Semester → 🔴 (1–8) · Academic Program → 🔵 · Academic Year → 🔵 · **Frequency → 🟢 Lookup (`fee_frequency`)**.

### Fee Payments
- **Pages:** List, Add, Edit.
- **Filters:** Department, Program, Payment Status, Fee Type, Has Payment Proof, Proof Awaiting Verification, Trashed.
- **Row actions:** Edit (paid challan pe sirf super admin) · **Preview Challan** · **Download PDF** · **View Proof** · **Mark Paid** (+student ko notification) · Delete.
- **Upar ke buttons:** **Generate Dept-wise Challans** (ek click, poore dept ke active students; unpaid duplicate skip) · Export · New.
- **Bulk:** **Mark as Paid** · **Download Challans (1 PDF)** · Delete.
- **Dropdowns:** Student → 🔵 · Fee Type → 🔴 · Semester → 🔴 · Academic Year → 🔵 · Fee Structure → 🔵 · Payment Status → 🔴 · Payment Method → 🔴.
- Nav badge (red) = overdue challans ka count.

### Scholarships / Scholarship Awards
- **Scholarships:** List/Add/Edit. Dropdown: Type → 🔴.
- **Scholarship Awards:** List/Add/Edit. **Approve** action (student ko notify). Dropdowns: Scholarship → 🔵 · Student → 🔵 · Academic Year → 🔵 · Status → 🔴.

### Fee Reports (page)
Read-only dashboard: Total Billed / Collected / Outstanding / Overdue, aaj/is mahine collection, aur outstanding challans ki list.

### Student Account / Ledger (page)
Registration/Roll number search → us student ka poora hisaab (billed/paid/outstanding) + har challan (status + PDF).

### Fee Slip Templates
Challan design. Dropdowns: Design Variant → 🔴 (kiu/classic) · Page Orientation → 🔴.

## 🌐 Website Management

### Website Pages
- **Pages:** List + Edit **only** (Add band — pages pehle se maujood). 
- **Row actions:** **View/Preview** (nayi tab) · Edit. Table mein **Published** toggle.
- **Form:** page ke hisaab se badalta hai — Home ke liye Hero Slides/Feature Cards/About/Programs-News-Events; Gallery ke liye Gallery Images; baaki pages ke liye Intro + Body content.
- **Dropdown:** Gallery Category → 🔴 (campus/labs/sports/events).

### News
- List/Add/Edit. Filters: Category, Published, Featured. **Publish** action (ek click). Dropdown: Category → **🟢 Lookup (`news_category`)**.

### Notices (Announcements)
- List/Add/Edit. Filters: Audience, Published. Form: audience, priority, department (conditional), publish/expiry, send-email toggle, content.
- Dropdowns: Target Audience → 🔴 (all/students/teachers/department) · **Priority → 🟢 Lookup (`priority_level`)** · Department → 🔵.

### Events
- List/Add/Edit. Filters: Published, Featured. Form: title, start/end datetime, venue, organizer, image, description. (Koi dropdown nahi.)

### Home Sections
- List + Edit only (3 fixed sections: Elevate Learning, Campus Life, Testimonials). Har section ka **Active** toggle + apna editor. _(Note: neeche "Home page" section dekhein — ye abhi live home par show nahi hote.)_

### Downloads
- List/Add/Edit. Filter: Category, Active. Form: title, description, category, file upload, sort order. Dropdown: Category → 🔴 (admission/academic/administrative/general).

### Contact Messages
- List + View only (public form se aate hain). **View** auto-read mark karta hai. Nav badge = unread count.

## ⚙️ System

### Settings — (Part 3 mein poori tafseel)

### Lookup Values (🟢 sab admin-editable dropdowns ka ghar)
- List/Add/Edit. Har row: **Category** (kaunsa dropdown), **Value** (DB key), **Display Label** (jo dikhe), **Sort Order**, **Active**.
- **Naya option add karne ke steps:**
  1. System → **Lookup Values** → **New**
  2. **Category** chunein (jaise "Teacher Designation")
  3. **Value** likhein (chhota, jaise `visiting_professor`)
  4. **Display Label** (jaise "Visiting Professor")
  5. Sort Order optional, **Active** on → Save.
  6. Foran us form ke dropdown mein aa jayega. (Active off = chhup jaata hai bina delete kiye.)
- **Groups jo abhi dropdowns se jude hain:** `province` (Student/Teacher), `qualification_level` (Student → Previous Qualification), `teacher_qualification` (Teacher → Highest Degree), `teacher_designation` (Teacher → Designation), `fee_frequency` (Fee Structure), `news_category` (News), `priority_level` (Announcement).

### Roles (Permissions) — super admin
List/Add/View/Edit. Har role ke liye per-module permission checkboxes + Select-All/Clear-All. super_admin delete nahi ho sakta.

### Activity Logs — super admin
List only (read-only). Filters: Type, Level, Date range. **Clear All Logs** button.

---

# PART 2 — Dropdowns: kaunsa aap add kar sakte hain vs developer

## 🟢 Aap khud add/edit kar sakte hain (System → Lookup Values)
| Dropdown | Kis form mein | Lookup group |
|---|---|---|
| Province | Student, Teacher | `province` |
| Previous Qualification | Student | `qualification_level` |
| Highest Degree | Teacher | `teacher_qualification` |
| Designation | Teacher | `teacher_designation` |
| Frequency | Fee Structure | `fee_frequency` |
| Category | News | `news_category` |
| Priority | Announcement/Notice | `priority_level` |

_(Aur bhi lookup groups seeded hain — `student_group`, `education_board`, `campus_location`, etc. — jo abhi kisi form se nahi jude, future ke liye hain.)_

## 🔵 Options doosre module se aate hain (wahan record banao)
Department, Academic Program, Academic Year, Student, Scholarship, Fee Structure — inke dropdowns ke options in modules ke records hote hain. Naya chahiye to us module mein add karo.

## 🔴 Fixed (developer chahiye)
Ye code mein tay hain: Gender, Blood Group, Department Type, Degree Type, Admission Category, Admission Type, Student Status, Employment Type, Teacher Status, Fee Type, Payment Status, Payment Method, Scholarship Type/Status, Semester (1–8), BPS (1–22), aur kuch hardcoded (Admission Inquiry Qualification/Status, Announcement Audience, Download Category, Fee Slip Variant/Orientation, Gallery Category) aur saare Settings-page dropdowns.

---

# PART 3 — Settings (super admin) — har field

**Admin → System → Settings.** Har field `CollegeSetting` mein save hota hai; save par site foran update.

### College Information
Naam (English/Urdu), Short Name, Principal, Address, City, Phone, Email, Website, **Established Year** (isse "Years of Excellence" stat banta hai), Affiliated University, Accreditation, **Logo** (upload).

### Academic Settings
Current Academic Year, Current Semester, Min Attendance %, Passing Marks %, Max Exam Marks, Working Days/Week.

### Fee Settings
Late Fine/Day, Grace Days, **Bank Name / Account Number / Account Title / Branch** (challan par), 1-Bill Prefix, Reference Prefix, aur **Scan-to-Pay QR**: enable toggle, **Raast/Merchant ID**, **QR Scheme ID (GUID)**, **Merchant City**. (Bank se Raast merchant ID mile to yahan daalein → QR se payment live.)

### Website Appearance
- Footer About Text, Footer Copyright.
- **🎨 Quick Theme Preset** (10 tayaar combos — Brand+Accent ek saath bhar deta hai).
- **Brand Color** (navbar **aur** footer) — Navy/Emerald/Royal Blue/Forest Green/Indigo/Oxford Blue/Maroon/Wine.
- **Accent Color** — Gold/Amber/Coral/Sky Blue/Ruby.
- **Body Background** — 8 options.
- **Body Font** (Open Sans/Inter/Nunito/Lato) · **Heading Font** (Playfair/Merriweather/Lora/Source Serif).
- _(Footer color khud brand se match hota hai; ek "dark brand" shade CTA ke liye auto ban jaati hai.)_

### Library Settings
Issue days (student/teacher), late fine/day, max books (student/teacher). _(Library module abhi active nahi — ye future ke liye.)_

### System Settings
Timezone (Asia/Karachi…), Date Format, Currency (PKR/USD/GBP), Default Language (en/ur).

**Color/theme badalna:** Settings → Website Appearance → color/preset chunein → **Save**.

---

# PART 4 — Public Website: page-by-page content

## Home page — section by section (deep)

Home page abhi **4 hisse** dikhata hai (order mein):

**1. Hero Slider + "Latest Updates" panel**
- **Slider (images/title/description/buttons):** Website Pages → **Home → Hero Slides** (1–5 slides). Khali ho to default slides.
- **"Latest Updates" panel/ticker:** live — **Notices (Announcements)** + **Events** modules se.

**2. Stats Bar (Enrolled Students, Faculty, Departments, Years of Excellence)**
- **Automatic** — live counts se (active Students/Teachers/Programmes/Departments), aur "Years" = current year − **Established Year** (Settings). Manually edit nahi; data add karne se badalte hain.

**3. Featured Programmes**
- **Cards:** live **Academic Programs** (active) se. **Heading/subtitle:** Website Pages → Home → Programs section title/text.

**4. Latest News**
- **Articles:** live **News** (published/featured) se. **Heading/subtitle:** Website Pages → Home → News section title/text.

> ⚠️ **Zaroori note:** Website Pages → Home mein **Feature Cards** aur **About block ("Discover the Minds…")** edit to hote hain, aur **Home Sections** module mein **Elevate Learning / Campus Life / Testimonials** bhi edit hote hain — **magar ye abhi live home page par show nahi hote** (woh sections layout mein shaamil nahi kiye gaye). Agar aap chahte hain ke ye bhi home par dikhein, to main inhe layout mein add kar sakta hoon (bata dein).

## Baaki public pages — content kahan se

| Page | Link | Content kahan se badlein |
|---|---|---|
| About JDCA | `/about` | Website Pages → About JDCA |
| History & Geography | `/about/history` | Website Pages → History & Geography * |
| Mission & Vision | `/about/mission` | Website Pages → Mission & Vision * |
| Message from Principal | `/about/message` | Website Pages → Message from Principal * |
| Message (VC/Director) | `/about/director`, `/about/principal` | **Fixed (developer)** |
| Academic Programmes | `/programs` | Intro: Website Pages → Academic Programmes; cards: Academic Programs module |
| Departments | `/departments` | Departments module |
| Faculty | `/faculty` | Intro: Website Pages → Faculty; list: Teachers module |
| Campus Facilities | `/campus-facilities` | Website Pages → Campus Facilities * |
| Gallery | `/gallery` | Website Pages → Gallery (images) |
| Downloads | `/downloads` | Downloads module |
| Online Admission Form | `/admissions` | Intro: Website Pages → Online Admission Form; submissions → Admission Inquiries |
| Admission Procedure | `/admissions/procedure` | Website Pages → Admission Procedure * |
| Fee Structure (public) | `/admissions/fee-structure` | Website Pages → Fee Structure * |
| Semester Rules | `/admissions/semester-rules` | Website Pages → Semester Rules * |
| Scholarships (public) | `/scholarships` | Website Pages → Scholarships * |
| News | `/news` | News module |
| Events | `/events` | Events module |
| Notices | `/notices` | Notices module |
| Contact | `/contact` | Intro: Website Pages → Contact; phone/email/address: Settings → College Information; submissions → Contact Messages |
| Fee Challan Download | `/fee-challan` | Automatic (Fee Payments data) |
| Jobs / Careers | `/jobs` | **Fixed (developer)** |

\* **"Smart" pages:** jab tak aap us page ka body **edit nahi karte, default design** dikhta hai; edit karte hi aap ka content live ho jaata hai. **Published** toggle = live/hidden; **Preview** se publish se pehle dekhein.

**Global (har page par):** naam/logo/favicon, theme color, fonts, footer → **Settings**. Top navigation menu ke naam/tarteeb **fixed** hain (developer).

---

# PART 5 — Student & Teacher Portals (maqsad)

### 🎓 Student Portal — `/portal/login`
Student office aaye baghair: **Fees & Challans** (status + PDF download with barcode/QR), **payment proof upload**, **Notices**, **Profile**, **password change**. Login: Reg Number · initial `123456`.

### 👩‍🏫 Teacher Portal — `/teacher/login`
Abhi: **Dashboard · Notices · Profile · password change** (halka hai — teaching modules hataye gaye the). Login: Employee ID/email · initial `123456`.

### Forgot Password (Email OTP) — dono portals
"Forgot password?" → reg/employee ID ya email → **email par 6-digit code (15 min)** → naya password. (Email tab jayega jab user ka email record par ho + Gmail SMTP set ho.) Warna admin **🔑 Set Portal Password** se reset karein.

---

# PART 6 — Aam kaam (Quick Reference)

| Kaam | Kahan |
|---|---|
| Naya student / bulk | Students → New / Download Template → Import |
| Dept-wise fee challan | Fee Payments → Generate Dept-wise Challans |
| Fee slips print (1 PDF) | Fee Payments → filter → select → Download Challans |
| Student ka fee hisaab | Student Account → reg number |
| Payment received | Fee Payments → Mark as Paid |
| Password reset (student/teacher) | Students/Teachers → 🔑 Set Portal Password |
| **Dropdown mein naya option** | System → Lookup Values → New (agar 🟢) |
| Theme/color | Settings → Website Appearance |
| Logo/naam/phone | Settings → College Information |
| Bank/QR | Settings → Fee Settings |
| Page ka text | Website Pages → page → edit → Publish |
| Home slider | Website Pages → Home → Hero Slides |
| News/Notice/Event | woh module → New → Publish |
| Naye staff ko access | Roles → user + role |

---

## Notes
- **Hataye gaye modules:** Exams, Attendance, Library, Timetable, LMS.
- **Home page:** abhi Feature Cards / About / Elevate / Campus Life / Testimonials live home par nahi dikhte (layout mein shaamil nahi) — chahein to add karwa lein.
- **Navigation menu** ka structure fixed hai (developer).
- **Email** (OTP + notifications) ke liye Gmail SMTP zaroori hai.
- Naya feature chahiye (WhatsApp, teacher portal expand, dynamic menu, home sections live karna) → developer se rabta.
