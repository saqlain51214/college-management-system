# Jinnah Degree College Astore — Complete System Guide

_College ke admin/staff ke liye mukammal reference. Har module, har public page, content kahan se badalta hai, portals, links aur passwords — sab aasan zabaan mein. Future mein bhi kaam aayega._

---

## 🔑 Quick Access — Links & Logins

| Kya | Link | Login | Password |
|---|---|---|---|
| **Admin Panel** | `/admin` | `admin@admin.com` | `admin123` (super admin) |
| **Student Portal** | `/portal/login` | Registration Number | initial `123456` |
| **Teacher Portal** | `/teacher/login` | Employee ID (JDCA-T-001) ya email | initial `123456` |
| **Public Website** | `/` | — | — |

> `<aapki-website>` = `https://college-management-system-production-7fa1.up.railway.app`
> Passwords har account ke liye **initial** hain — user "Forgot password?" (email OTP) se ya admin 🔑 button se badal sakte hain.

---

# PART 1 — Admin Panel Modules

Sidebar 6 groups mein ba- ta hua hai. Har module ka **maqsad + kya kar sakte hain**.

## 🏛️ College Setup
- **Departments** — shobe (jaise Department of Education). Add/edit; `show on website` se public visibility; sort order. Programmes aur teachers isi se jurte hain.
- **Academic Programs** — programmes (ADE, B.Ed, etc.), har ek **ek department** se linked. Ye admission form + student add + website menu mein aate hain. `show on website` se dikhana control.

## 👨‍🎓 Students & Admissions
- **Students** — markazi student record. New / Import (Excel) / Download Template / Export. Naya student banate hi **portal login** ban jaata hai (username = reg number, password = 123456). Filter tabs (All/Unique/Duplicates/No Reg/No Roll). 🔑 **Set Portal Password** button. Select kar ke **Generate Challans** / **Download Fee Challans**.
- **Admission Inquiries** — website ke online admission form ki requests (documents ke saath) ka inbox. Sirf review — admit karna ho to student ko **Students** mein add karte hain.

## 👩‍🏫 Faculty & Staff
- **Teachers** — teacher/staff record. New / Import / Template / Export. Department assign. 🔑 **Set Portal Password** button. Teacher portal login banta hai.

## 💰 Finance
- **Fee Structure** — programme/semester ki fee rate list (reference).
- **Fee Payments** — har challan + payment. **Generate Dept-wise Challans** (ek click, poore dept ke active students; duplicate skip). **Mark as Paid**. **Download Challans (1 PDF)**. Filters (dept/program/status). Paid challan lock. Har challan par barcode + QR.
- **Scholarships / Scholarship Awards** — programs aur kis student ko mila (record; fee khud kam nahi hoti — discount alag).
- **Fee Reports** — total billed / collected / outstanding / overdue.
- **Student Account (Ledger)** — reg number search → us student ka poora fee hisaab + har challan PDF.
- **Fee Slip Templates** — challan design (bank, logo, fields, barcode, QR). Ek active template.

## 🌐 Website Management
- **Website Pages** — public site ke pages ka content (details Part 2 mein).
- **News** / **Notices (Announcements)** / **Events** — add/edit + Publish + dates.
- **Home Sections** — home page ke beech ke blocks (on/off + order).
- **Downloads** — downloadable files (forms/prospectus).
- **Contact Messages** — website contact form ka inbox.

## ⚙️ System
- **Settings** _(super admin)_ — college identity, theme/colors, fonts, fee/bank, QR. (Part 3.)
- **Lookup Values** — chhoti dropdown lists (foran forms mein aati hain).
- **Roles (Permissions)** _(super admin)_ — kaun kya kar sakta hai; checkbox se; naye staff ko role dena.
- **Activity Logs** _(super admin)_ — kisne kya kiya; **Clear All Logs** button.

---

# PART 2 — Public Website: har page + content kahan se

**Zaroori usool:** website ke **do hisse** hain —
1. **Content jo aap badal sakte hain** — admin panel se (Website Pages, News, Settings, etc.).
2. **Structure jo fixed hai** — top navigation menu ke items/naam/tarteeb design mein fixed hain (developer ke baghair nahi badalte). Aap pages ko **publish/unpublish** kar sakte hain (jisse unke menu link dikhte/chhupte hain).

## Page-by-page content map

| Public Page | Link | Kya dikhata hai | Content kahan se / kaise badlein |
|---|---|---|---|
| **Home** | `/` | Hero slider, feature cards, About block, stats, latest News/Events/Programmes | **Website Pages → Home** (hero slides, cards, about); stats **khud** live DB se; **Home Sections** (beech ke blocks); News/Events/Programmes apne modules se |
| **About JDCA** | `/about` | College intro + stats | **Website Pages → About JDCA** (intro/body); stats live |
| **History & Geography** | `/about/history` | College ki tareekh | **Website Pages → History & Geography** (body edit karein) * |
| **Mission & Vision** | `/about/mission` | Mission/vision | **Website Pages → Mission & Vision** * |
| **Message from Principal** | `/about/message` | Principal ka paigham | **Website Pages → Message from Principal** * |
| **Message (VC/Director)** | `/about/director`, `/about/principal` | Paigham | **Abhi fixed (hardcoded)** — developer chahiye |
| **Academic Programmes** | `/programs` | Programmes ki list | Intro: **Website Pages → Academic Programmes**; cards: **Academic Programs** module |
| **Departments** | `/departments`, `/departments/{slug}` | Departments + detail | **Departments** module (`show on website`) |
| **Faculty** | `/faculty` | Teachers ki list | Intro: **Website Pages → Faculty**; list: **Teachers** module |
| **Campus Facilities** | `/campus-facilities` | Facilities | **Website Pages → Campus Facilities** (body) * |
| **Gallery** | `/gallery` | Tasveerein | **Website Pages → Gallery** (gallery images repeater) |
| **Downloads** | `/downloads` | Files | **Downloads** module |
| **Online Admission Form** | `/admissions` | Admission form | Intro: **Website Pages → Online Admission Form**; form bharne par → **Admission Inquiries** inbox |
| **Admission Procedure** | `/admissions/procedure` | Dakhle ka tareeqa | **Website Pages → Admission Procedure** (body) * |
| **Fee Structure (public)** | `/admissions/fee-structure` | Fees ki tafseel | **Website Pages → Fee Structure** (body) * |
| **Semester Rules** | `/admissions/semester-rules` | Rules | **Website Pages → Semester Rules** (body) * |
| **Scholarships (public)** | `/scholarships` | Scholarship info | **Website Pages → Scholarships** (body) * |
| **News** | `/news`, `/news/{slug}` | Khabrein | **News** module (Publish) |
| **Events** | `/events` | Events | **Events** module (Publish) |
| **Notices** | `/notices` | Notices | **Notices (Announcements)** module (Publish + expiry) |
| **Contact** | `/contact` | Contact form + address/phone/email | Intro: **Website Pages → Contact**; address/phone/email: **Settings → College Information**; form → **Contact Messages** inbox |
| **Fee Challan Download** | `/fee-challan` | Student reg number se apna challan | Automatic (Fee Payments data se) |
| **Jobs / Careers** | `/jobs` | Naukriyaan | **Abhi fixed (hardcoded)** — applications email hoti hain |
| **Search** | `/search` | Site search | Automatic |

\* **Ye pages "smart" hain:** jab tak aap us page ka **body edit nahi karte, ek default design** dikhta hai. Jaise hi aap **Website Pages** mein us page ka content likhte/save karte hain, aapka content default ki jagah live ho jaata hai. (Isse page kabhi khaali nahi hota.)

## Publish / Preview (har page par)
- **Website Pages** mein har page ka **Published** toggle:
  - **ON** = page website par live + uska menu link dikhta hai.
  - **OFF** = page 404 + menu se link chhup jaata hai (aap phir bhi **Preview** se dekh sakte hain).
- News/Notices/Events mein bhi **Publish** toggle + tareekhein.

---

# PART 3 — Content Source Master Table (kaha se kya badlega)

| Aap kya badalna chahte hain | Kahan jayein |
|---|---|
| College ka **naam / logo / favicon** | Settings → College Information |
| **Address / phone / email** (footer + contact page) | Settings → College Information |
| **Theme color** (navbar + footer + site) | Settings → Website Appearance → Save |
| **Fonts / background** | Settings → Website Appearance |
| **Footer text / copyright** | Settings → Website Appearance |
| **Bank account / branch / fee QR (Raast) ID** | Settings → Fee Settings |
| **Home page slider (hero images)** | Website Pages → Home → Hero slides |
| **Home feature cards / About block** | Website Pages → Home |
| **Home ke beech ke sections** | Home Sections |
| **Home stats numbers** | Automatic (live DB — students/faculty/programmes count) |
| **Kisi page ka text** (About/History/Facilities/Rules/Scholarships etc.) | Website Pages → woh page → body edit → Save |
| **Gallery images** | Website Pages → Gallery |
| **Departments / Programmes** | College Setup ke modules |
| **Faculty list** | Teachers module |
| **News / Notices / Events** | Website Management ke modules |
| **Downloadable files** | Downloads module |
| **Top navigation menu ke naam/tarteeb** | **Fixed (developer)** — sirf publish/unpublish se link dikhte/chhupte hain |

---

# PART 4 — Student & Teacher Portals (maqsad)

## 🎓 Student Portal — `/portal/login`
**Maqsad:** Student office aaye baghair apni cheezein khud dekhe/download kare.
- **Fees & Challans** — status (jama/baqi) + **challan PDF** (barcode + QR) download → bank jama karne ke liye
- **Payment proof upload** — jama karne ke baad receipt upload
- **Notices** — announcements
- **Profile** + **password change**
- **Login:** Registration Number · **Initial password:** `123456` (login page par show nahi hota)

## 👩‍🏫 Teacher Portal — `/teacher/login`
**Maqsad (abhi):** Notices aur apni profile dekhna. _(Abhi halka hai — attendance/assignments/results wale modules hata diye gaye the.)_
- Dashboard · Notices · Profile · Password change
- **Login:** Employee ID (JDCA-T-001) ya email · **Initial password:** `123456`

## Password bhool jayein — Forgot Password (Email OTP)
Dono portals par **"Forgot password?"**:
1. Reg number / Employee ID **ya email** daalein
2. Email par **6-digit code** aata hai (15 min)
3. Code + naya password → password badal jaata hai

> OTP email tab jayega jab (a) user ka **email record par ho** aur (b) college ka **Gmail SMTP set** ho (Settings/hosting).
> Agar login na ho: Admin → Students/Teachers → us row par **🔑 Set Portal Password** → `123456` set karein.

---

# PART 5 — Settings deep-dive (super admin)

**Admin → Settings** (sirf super admin):
- **College Information** — naam, logo, short name, address, phone, email. → poori site + slips + emails par asar.
- **Academic Settings** — passing marks, etc.
- **Fee Settings** — bank name/account/branch, late fine, reference prefix, **Scan-to-Pay QR** (enable + Raast/Merchant ID + city). Bank se Raast merchant ID mile to yahan daalein → QR se payment live.
- **Website Appearance** — **theme color** (swatch preview), accent, background, **fonts**, footer text/copyright. (Navbar + footer color ek saath.)
- **System / Library Settings.**

---

# PART 6 — Aam kaam (Quick Reference)

| Kaam | Kahan |
|---|---|
| Naya student | Students → New |
| Bulk students | Students → Download Template → Import |
| Dept-wise fee challan | Fee Payments → Generate Dept-wise Challans |
| Fee slips print (1 PDF) | Fee Payments → filter → select → Download Challans |
| Student ka fee hisaab | Student Account → reg number |
| Payment received | Fee Payments → Mark as Paid |
| Student/Teacher password reset | Students/Teachers → 🔑 Set Portal Password |
| Theme/color | Settings → Website Appearance |
| Logo / naam / phone | Settings → College Information |
| Bank / QR | Settings → Fee Settings |
| Kisi page ka text | Website Pages → page → edit → Publish |
| Home slider images | Website Pages → Home → Hero slides |
| News / Notice / Event | woh module → New → Publish |
| Naye staff ko access | Roles → user + role |
| Logs saaf | Activity Logs → Clear All Logs |

---

## Notes
- **Hata diye gaye modules** (istemal nahi the): Exams, Attendance, Library, Timetable, LMS.
- **Navigation menu ka structure fixed hai** — naye top-level menu item/naam ke liye developer chahiye. (Dynamic banana ho to bataayein.)
- **Email (OTP + notifications)** ke liye Settings/hosting mein **Gmail SMTP** set karna zaroori hai.
- Naya feature (WhatsApp notifications, teacher portal expand, dynamic menu) chahiye → developer se rabta.
