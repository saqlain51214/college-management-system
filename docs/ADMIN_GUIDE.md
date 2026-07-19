# Jinnah Degree College Astore — Admin Panel Guide

_Ye guide college ke admin/staff ke liye hai. Har module kya karta hai aur aap kya-kya kar sakte hain, aasan zabaan mein._

**Admin panel ka pata:** `https://<aapki-website>/admin`
**Login:** apna email + password. (Super Admin: `admin@admin.com`)

> **Tip:** Upar dayeen taraf 🌙/☀️ button se dark/light theme badal sakte hain. Sidebar ke groups (College Setup, Finance, etc.) collapse/expand hote hain.

---

## 📊 Dashboard (Home screen)

Login karte hi jo screen aati hai. Yahan ek nazar mein college ka haal dikhta hai:

- **Active Students** — kitne students active hain
- **Faculty & Staff** — kitne teachers hain
- **Fee Collected (This Month)** — is mahine kitni fees jama hui
- **Pending Fees** aur **Overdue Challans** — kitni fees baqi/late hai
- **New Admission Inquiries** — website se kitni nayi admission requests aayi
- **Active Scholarships** — kitne scholarship program chal rahe

Neeche charts: student enrollment aur fee collection ka trend, aur recent fee payments.

---

## 🏛️ College Setup

### Departments (Shobajaat)
**Kya hai:** College ke departments (jaise Department of Education).
**Kya kar sakte hain:**
- Naya department **New** se add karein (naam, description, image).
- **Show on website** on/off — department website par dikhe ya nahi.
- **Sort order** se website par tarteeb set karein.
- Edit/delete.

### Academic Programs (Programmes)
**Kya hai:** Programmes (jaise ADE, B.Ed 1.5 Year, AD Health & Physical Education).
**Kya kar sakte hain:**
- Naya programme add karein aur usay ek **department** se jodein.
- **Show on website** se admissions/website par dikhana control karein.
- Yehi programmes admission form aur student add karte waqt dropdown mein aate hain.

---

## 👨‍🎓 Students & Admissions

### Students
**Kya hai:** Poore college ke students ka record.
**Kya kar sakte hain:**
- **New** — ek student manually add karein (naam, walid, registration number, programme, department, phone, etc.). Naya student banate hi uska **portal login** ban jaata hai: username = **registration number**, password = **123456**.
- **Import (Excel/CSV)** — ek saath bahut se students upload karein.
- **Download Template** — pehle template download karein, usmein students bharein, phir Import karein. (Template mein department-wise students pehle se maujood hain.)
- **Export** — poora student data Excel mein nikaalein.
- **Filter tabs**: All / Unique / Duplicates / No Registration / No Roll — ek-ek kar ke reconcile karne ke liye.
- **Reg No.** column aapka asli number dikhata hai (Roll No. hidden, "Toggle columns" se dekh sakte hain).
- Kisi student ko select kar ke **Generate Challans** (fee challan banayein) ya **Download Fee Challans (1 PDF)**.

> **Reg No. vs Roll No.:** Jo number aapne diya (jaise 4267) woh **Registration Number** hai — yehi har jagah dikhta hai. Roll number system ne khud banaya tha, woh chhupa hua hai.

### Admission Inquiries
**Kya hai:** Website ke online admission form se aayi requests (documents ke saath).
**Kya kar sakte hain:**
- Har inquiry dekhein (naam, programme, uploaded documents, reference number).
- Status update/review karein. Ye sirf inbox hai — student ko yahan se admit nahi karte; unhe **Students** mein add karte hain.

---

## 👩‍🏫 Faculty & Staff

### Teachers
**Kya hai:** Teachers/staff ka record.
**Kya kar sakte hain:**
- **New** se teacher add karein, ya **Import / Download Template / Export** (students ki tarah).
- Teacher ko department assign karein.
- Teacher ka portal login bhi banta hai.

---

## 💰 Finance

### Fee Structure
**Kya hai:** Kis programme/semester ki kitni fees hai — ye define karte hain.
**Kya kar sakte hain:** Fee heads (tuition, admission, etc.) aur amounts set karein, jinke hisaab se challan bante hain.

### Fee Payments (sabse important)
**Kya hai:** Har student ka har fee challan yahan hota hai.
**Kya kar sakte hain:**
- **Generate Dept-wise Challans** (upar green button) — ek click mein poore department (ya ek programme) ke sab active students ka challan banayein. Amount, due date, semester, fee type set karein. Jinke paas pehle se unpaid challan hai, woh skip ho jaate hain (double billing nahi).
- Kisi challan ko **Mark as Paid** karein (payment aane par).
- Select kar ke **Download Challans (1 PDF)** — sab challan ek hi PDF mein print.
- **Department / Programme / Status** se filter karein.
- **Reg No.** aur **Receipt No.** columns.
- Paid challan lock ho jaata hai (sirf super admin edit kar sakta hai) — galti se badalne se bacha.

> Har challan par **barcode** (bank counter ke liye) aur **"Scan to Pay" QR** hota hai.

### Scholarships & Scholarship Awards
**Kya hai:** Scholarship programs aur kis student ko kaunsa scholarship mila.
**Kya kar sakte hain:** Naye scholarship programs banayein; students ko awards assign karein (amount, status, tareekh).

### Fee Reports
**Kya hai:** Fees ka overview — total billed, collected, outstanding, overdue.
**Kya kar sakte hain:** Aaj/is mahine ki collection, aur baqi (outstanding) challans ki list dekhein.

### Student Account (Fee Ledger)
**Kya hai:** Ek student ka poora fee hisaab, uska **registration number** daal kar.
**Kya kar sakte hain:** Reg number search karein → student ki profile + **Total Billed / Paid / Outstanding** + uske sab challan (status ke saath) + har challan ka PDF link.

### Fee Slip Templates
**Kya hai:** Fee challan/slip ka design (kaunsa bank, logo, colors, fields, barcode, QR).
**Kya kar sakte hain:**
- Bank logo, college logo, bank account, account title set karein.
- Barcode aur QR on/off (QR ki settings **Settings → Fee Settings** mein bhi hain).
- Kaunsa template **active** ho, woh chunein.

---

## 🌐 Website Management

_Yahan se poori public website ka content control hota hai._

### Website Pages
**Kya hai:** Har website page (Home, About, History, Mission, Message, Campus Facilities, Admission Procedure, Fee Structure, Semester Rules, Scholarships, etc.).
**Kya kar sakte hain:**
- Kisi bhi page ka content edit karein (rich text editor).
- **Hero slides** (Home ki bari tasveerein), **gallery images**, feature cards — add/edit/reorder.
- **Publish** on/off — page website par dikhe ya nahi.
- **Preview** — publish karne se pehle privately dekh lein (Preview Page button).

> **Important:** Kuch pages (History, Mission, Message, Campus Facilities, Admission Procedure, Fee Structure, Semester Rules, Scholarships) ka apna default design hai. Jab tak aap us page ka **body edit** nahi karte, default design dikhta rahega. Jaise hi aap content likhte hain, aapka content aa jaata hai.

### News (Khabrein) & Notices (Announcements)
**Kya kar sakte hain:** News articles aur notices add karein. **Publish**, **featured**, category, tareekh set karein. Notices ki **expiry date** bhi.

### Events
**Kya kar sakte hain:** College events add karein (venue, date/time, publish).

### Home Sections
**Kya hai:** Home page ke beech ke sections (Elevate Learning, Campus Life, Testimonials).
**Kya kar sakte hain:** On/off karein, tarteeb badlein, content edit karein.

### Downloads
**Kya kar sakte hain:** Downloadable files (forms, prospectus) add karein — category aur tarteeb ke saath. Website ke Downloads page par aate hain.

### Contact Messages
**Kya hai:** Website ke contact form se aaye messages ka inbox.

---

## ⚙️ System

### Settings (College Settings) — _sirf Super Admin_
Yahan se poore system ki bunyadi cheezein:
- **College Information** — naam, logo, address, phone, email.
- **Academic Settings** — passing marks, etc.
- **Fee Settings** — bank name/account, late fine, reference prefix, aur **QR settings** (Raast/Merchant ID, jab bank se mile).
- **Website Appearance** — **Theme color (color change yahan se)**, accent color, background, fonts. (Navbar aur footer ka color yahin se ek saath badalta hai.)
- **Library / System Settings.**

**Color change karne ke liye:** Settings → Website Appearance → Theme color chunein (swatch preview ke saath) → **Save**.

### Lookup Values
**Kya hai:** Choti dropdown lists (jaise categories) jo forms mein aati hain.
**Kya kar sakte hain:** In lists mein items add/edit karein — foran forms mein aa jaate hain.

### Roles (Permissions) — _sirf Super Admin_
**Kya hai:** Kaun kya dekh/kar sakta hai, iska control.
**Kya kar sakte hain:**
- Roles: **super_admin** (sab kuch), **admin** (rozmarra kaam — students, fees, website; settings/roles nahi), **panel_user** (sirf website/CMS + inquiries).
- Kisi role ko checkbox se permission dein/hatayein.
- Naye staff ko user banayein aur role assign karein — bina developer ke.

### Activity Logs — _sirf Super Admin_
**Kya hai:** System mein kya-kya hua (kisne kya badla) ka record.
**Kya kar sakte hain:** Logs dekhein; **Clear All Logs** button se sab logs delete karein (confirm ke baad).

---

## 🎓 Student & Teacher Portals

- **Student portal:** `/portal/login` — username = registration number, password = `123456` (initially). Student apni fees, challan (PDF/barcode/QR), aur profile dekh sakta hai.
- **Teacher portal:** `/teacher/login`.

---

## ⭐ Aam kaam — jaldi guide (Quick Reference)

| Kaam | Kahan jayein |
|---|---|
| Naya student add karna | Students → New |
| Bahut se students add karna | Students → Download Template → bharein → Import |
| Department-wise fee challan banana | Fee Payments → Generate Dept-wise Challans |
| Fee slips print (1 PDF) | Fee Payments → filter → select all → Download Challans |
| Kisi student ka fee hisaab dekhna | Student Account → reg number search |
| Payment received mark karna | Fee Payments → challan → Mark as Paid |
| Theme/color badalna | Settings → Website Appearance → Save |
| College naam/logo/phone badalna | Settings → College Information |
| Bank account / QR set karna | Settings → Fee Settings |
| Website page ka text badalna | Website Pages → page → edit → Publish |
| Home page slider tasveerein | Website Pages → Home → Hero slides |
| News/Notice lagana | News / Notices → New → Publish |
| Naye staff ko access dena | Roles → user + role assign |
| Activity logs saaf karna | Activity Logs → Clear All Logs |

---

_Note: Ye system se ab hata diye gaye hain (istemal nahi ho rahe the): Exams, Attendance, Library, Timetable, LMS. Agar future mein chahiye to developer add kar sakta hai._

_Kisi cheez mein madad ya naya feature (jaise WhatsApp notifications) chahiye to developer se rabta karein._
