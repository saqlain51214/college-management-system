# Jinnah Degree College Astore — Admin Panel Guide

_Ye guide college ke admin/staff ke liye hai. Har module ka **maqsad**, uski **business logic (andar se kaise kaam karta hai)**, aur **aap kya-kya kar sakte hain** — aasan zabaan mein._

**Admin panel:** `https://<aapki-website>/admin` · **Super Admin:** `admin@admin.com`
**Student portal:** `/portal/login` · **Teacher portal:** `/teacher/login`

> Upar dayeen taraf 🌙/☀️ se theme, sidebar groups collapse/expand.

---

## 📊 Dashboard
**Maqsad:** Login karte hi college ka poora haal ek nazar mein.
**Business logic:** Ye numbers **live database** se aate hain — koi manual entry nahi. "Fee Collected (This Month)" sirf un challans ka jod hai jo is mahine **Paid** hue; "Overdue Challans" woh hain jinki due date guzar chuki aur paise nahi aaye; "New Admission Inquiries" is mahine website se aayi requests.

---

## 🏛️ College Setup

### Departments
**Maqsad:** College ke shobe.
**Business logic:** Har department ek **container** hai jisse programmes aur teachers jurte hain. `show_on_website` flag decide karta hai website par dikhega ya nahi; `sort_order` tarteeb. Department delete karne se pehle uske programmes/students ka khayal rakhein (woh us se linked hote hain).
**Actions:** New/Edit/Delete, website par dikhana on/off, tarteeb.

### Academic Programs
**Maqsad:** Programmes (ADE, B.Ed 1.5, AD Health & PE, etc.).
**Business logic:** Har programme **ek department** se belong karta hai (parent-child). Yehi programmes 3 jagah use hote hain — (1) student add/import karte waqt, (2) admission form ka dropdown, (3) website "Academics → Departments → Programmes" menu. `show_on_website` se public visibility control hoti hai.
**Actions:** Add/Edit, department se link, website visibility.

---

## 👨‍🎓 Students & Admissions

### Students
**Maqsad:** Poore college ke students ka markazi record — baaki har cheez (fees, portal) isi se judi hai.
**Business logic (zaroori samajhna):**
- **Registration Number = student ki pehchaan.** Yehi number fee challan, portal login, aur reports mein use hota hai. (Roll number system ne khud banaya tha, isliye chhupa hua hai.)
- Student banate hi uska **portal account khud ban jaata hai** — username = registration number, password = **123456**. Alag se account banane ki zarurat nahi.
- **Status lifecycle:** Active → On Leave → Inactive. Sirf **Active** students bulk fee challan, dashboard counts, aur "active students" mein aate hain.
- Har student ek **programme + department** se linked hota hai — isi bina par dept-wise challan aur reports chalte hain.
- **Duplicate/No-Reg handling:** Jin students ka reg number nahi tha (35 duplicates), unhe alag tab/filter se ek-ek kar ke theek kiya jaata hai — taake ghalat billing na ho.
**Actions:** New · Import (Excel/CSV) · Download Template · Export · Filter tabs (All/Unique/Duplicates/No Reg/No Roll) · select → Generate Challans / Download Fee Challans (1 PDF).

### Admission Inquiries
**Maqsad:** Website ke online admission form ki requests ka inbox.
**Business logic:** Jab koi website par admission form bharta hai (documents ke saath), ek **AdmissionInquiry** record ban jaata hai + ek **reference number (JDCA-YYYY-XXXX)** milta hai, aur college ko email jaata hai (jab email on ho). **Ye khud student nahi banata** — ye sirf ek lead/request hai. Admin review kar ke, agar admit karna ho, to us student ko **Students** module mein manually add karta hai. (Do-step process: inquiry → admin decision → student.)
**Actions:** Inquiries dekho, documents kholo, status review.

---

## 👩‍🏫 Faculty & Staff

### Teachers
**Maqsad:** Teachers/staff ka record + unka portal.
**Business logic:** Har teacher ka **Employee ID** uski pehchaan hai (jaise JDCA-T-001). Teacher portal login = **Employee ID ya email** + default password **123456**. Teacher ko department assign kar sakte hain (shuru mein blank hota hai). Sirf **Active** teachers portal mein login kar sakte hain aur faculty count mein aate hain.
**Actions:** New · Import/Template/Export · department assign · edit/deactivate.

---

## 💰 Finance (sabse ahem hissa)

### Fee Structure
**Maqsad:** Programme/semester ke hisaab se fees ki tafseel.
**Business logic:** Ye "rate list" hai — kaunse fee head (tuition, admission, etc.) ki kitni raqam. Ye khud challan nahi banata; ye reference hai jiske hisaab se aap challan ki amount rakhte hain.

### Fee Payments (fee challans ka dil)
**Maqsad:** Har student ke har challan ka record aur uski payment.
**Business logic (poora samajhna):**
- **Net Payable = Amount Due + Fine − Discount.** Yehi asal wasool karne wali raqam hai.
- **Status lifecycle:** `Pending` (banaya, paisa nahi aaya) → `Paid` (poora aaya) / `Partial` (kuch aaya) / `Overdue` (due date guzar gayi, paisa nahi) / `Waived` (maaf).
- **Overdue khud lagta hai:** ek rozana automatic system (raat 1 baje) un Pending challans ko **Overdue** kar deta hai jinki due date guzar chuki, aur student/admin ko reminder bhejta hai (jab notifications on hon).
- **Bulk generation samajhdaar hai:** "Generate Dept-wise Challans" us department ke **sirf Active students** ke liye challan banata hai, aur jis student ke paas pehle se **usi fee type + semester ka unpaid challan** hai use **skip** kar deta hai — yani double billing nahi hoti.
- **Paid = locked:** jab challan Paid ho jaaye, woh lock ho jaata hai (sirf super admin edit/delete kar sakta hai) — taake record se chhed-chhaad na ho.
- Har challan par **barcode** (bank counter scan) + **"Scan to Pay" QR** hota hai; Paid mark karte hi student ko confirmation notification jaata hai (jab on ho).
**Actions:** Generate Dept-wise Challans · Mark as Paid · select → Download Challans (1 PDF) · Department/Programme/Status filters · Export.

### Scholarships & Scholarship Awards
**Maqsad:** Scholarship programs aur kis student ko mila.
**Business logic:** Do parts — **Scholarship** (program: naam, type, amount, seats) aur **Scholarship Award** (kis student ko, kis saal, kitna, kya status: approved/pending). Ye ek **record-keeping** hai — award dene se fee challan khud kam nahi hota; agar fee kam karni ho to us student ke challan par **discount** daalna hota hai.
**Actions:** Programs banao, students ko awards do (amount/status/tareekh).

### Fee Reports
**Maqsad:** Fees ka bird's-eye view.
**Business logic:** Live jod — **Total Billed** (sab net payable), **Collected** (sab amount paid), **Outstanding** (billed − collected), aur overdue count/amount. Neeche unpaid challans ki list (purane pehle).

### Student Account (Fee Ledger)
**Maqsad:** Ek student ka poora fee hisaab, reg number se.
**Business logic:** Reg number (ya roll) search karte hi us **ek student** ke saare challan nikaal kar Total Billed / Paid / Outstanding jod deta hai, har challan ka status + PDF link. Ye "customer statement" jaisa hai.
**Actions:** Reg number search → statement dekho, challan PDF kholo.

### Fee Slip Templates
**Maqsad:** Challan/slip ka design.
**Business logic:** Ek waqt mein ek template **active** hota hai — wahi sab challans par lagta hai. Template mein bank logo, college logo, bank account/title, fields, aur barcode/QR on-off hote hain. **Logo/QR fallback:** agar aap logo upload na karein to default logo lagta hai; QR ke liye merchant ID Settings se aata hai.
**Actions:** Template edit, active chuno, logos/bank set karo, barcode/QR on-off.

---

## 🌐 Website Management (public website ka control)

### Website Pages
**Maqsad:** Har website page ka content.
**Business logic (khaas):**
- **Publish flag** decide karta hai page public ko dikhega ya 404. Publish se pehle **Preview** (aap khud dekh lein).
- **Kuch pages ka apna default design hai** (History, Mission, Message, Campus Facilities, Admission Procedure, Fee Structure, Semester Rules, Scholarships). Rule: **jab tak aap us page ka body edit nahi karte, default design dikhta hai; jaise hi aap content likhte hain, aapka content default ko replace kar deta hai.** Isliye galti se page khaali nahi hota.
- Home page ke **hero slides, gallery, feature cards** repeaters se add/reorder hote hain.
**Actions:** Content edit (rich text), hero slides/gallery, Publish/Preview.

### News · Notices (Announcements) · Events
**Business logic:** In sab mein ek **publish flag** aur tareekh hoti hai — sirf published cheezein website par aati hain. Notices/Events ki **expiry/end date** guzar jaye to woh apne aap list se hat jaate hain. Announcement publish karte hi students ko in-app notification ja sakta hai.
**Actions:** Add/Edit, Publish, featured, category, dates.

### Home Sections
**Business logic:** Home page ke beech ke blocks (Elevate Learning, Campus Life, Testimonials). Har block ka **is_active** toggle aur `sort_order` — off karo to woh section home se ghayab.

### Downloads
**Business logic:** File list category + sort se website ke Downloads page par group ho kar aati hai; `is_active` off = chhup jaati hai.

### Contact Messages
**Business logic:** Website contact form → seedha ye inbox + college ko email. Sirf padhne ke liye.

---

## ⚙️ System

### Settings (College Settings) — _sirf Super Admin_
**Maqsad:** Poore system ki bunyadi settings.
**Business logic:** Ye ek **key-value store** hai jo poori site ko drive karta hai — College Information (naam/logo/address/phone/email), Fee Settings (bank, late fine, QR merchant ID), aur **Website Appearance** (theme color, accent, background, fonts). **Theme color yahin se badalta hai — navbar aur footer dono ek saath.** Ek jagah badlo, poori site (public + slips + emails) par asar.
**Color change:** Settings → Website Appearance → color chuno (preview ke saath) → Save.

### Lookup Values
**Business logic:** Choti dropdown lists jo forms mein aati hain. Yahan item add karte hi woh foran metadata dropdowns mein aa jaata hai — **dynamic**, developer ki zarurat nahi.

### Roles (Permissions) — _sirf Super Admin_
**Maqsad:** Kaun kya kar sakta hai.
**Business logic:** 3 tayaar roles — **super_admin** (sab kuch, koi rok nahi), **admin** (rozmarra: students/fees/website — magar settings/roles/permanent-delete nahi), **panel_user** (sirf website/CMS + inquiries). Har resource/action ka ek permission hota hai; role ko checkbox se woh permission de/le sakte hain. Naya staff = naya user + role assign — **bina developer ke**.
**Actions:** Roles edit (checkboxes), users banao, role assign.

### Activity Logs — _sirf Super Admin_
**Business logic:** System khud record karta hai ke kisne kya create/update/delete kiya (audit trail). Purane logs 60 din baad khud saaf hote hain; aap **Clear All Logs** se foran sab hata sakte hain.

---

## 🎓 Portals (login logic)

### Student Portal — `/portal/login`
- **Login ID:** Registration Number (roll number bhi chalta hai).
- **Default password:** `123456` (sab students ke liye shuru mein).
- Student apni fees, challan (PDF + barcode + QR), aur profile dekhta hai. Password khud badal sakta hai.

### Teacher Portal — `/teacher/login`
- **Login ID:** **Employee ID** (jaise `JDCA-T-001`) **ya email**.
- **Default password:** `123456` (sab teachers ke liye).
- Password khud badal sakte hain (default 123456 daal kar naya set karein).

---

## 🔔 Notifications (background logic)
System khud kuch events par notifications bhejta hai (email + in-app bell, jab email on ho): **fee paid confirmation**, **fee overdue reminder**, **new announcement**. Iske templates code mein hain (manual editing wali screen hata di gayi). Email tab hi jaata hai jab Settings/hosting mein **SMTP (Gmail)** set ho.

---

## ⭐ Aam kaam — Quick Reference

| Kaam | Kahan |
|---|---|
| Naya student | Students → New |
| Bahut se students | Students → Download Template → bharo → Import |
| Dept-wise fee challan | Fee Payments → Generate Dept-wise Challans |
| Fee slips print (1 PDF) | Fee Payments → filter → select all → Download Challans |
| Student ka fee hisaab | Student Account → reg number search |
| Payment received | Fee Payments → challan → Mark as Paid |
| Theme/color badalna | Settings → Website Appearance → Save |
| College naam/logo/phone | Settings → College Information |
| Bank account / QR | Settings → Fee Settings |
| Website page text | Website Pages → page → edit → Publish |
| Home slider images | Website Pages → Home → Hero slides |
| News/Notice | News / Notices → New → Publish |
| Naye staff ko access | Roles → user + role |
| Logs saaf | Activity Logs → Clear All Logs |
| Teacher login | /teacher/login · Employee ID/email · password 123456 |
| Student login | /portal/login · Reg number · password 123456 |

---

_Hata diye gaye modules (istemal nahi ho rahe the): Exams, Attendance, Library, Timetable, LMS. Naya feature (jaise WhatsApp notifications) chahiye to developer se rabta karein._
