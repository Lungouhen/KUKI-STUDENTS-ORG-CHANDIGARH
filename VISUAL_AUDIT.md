# KSO CHANDIGARH — VISUAL UI/UX AUDIT & COMPONENT REPORT

This document provides a comprehensive visual audit of the **KSO Chandigarh NGO Website, Membership Management System, and Admin CMS**.

---

## 🎨 1. DESIGN SYSTEM & PALETTE COMPLIANCE

| Element | CSS Variable / Value | Visual Application |
|---------|-----------------------|--------------------|
| **Primary Theme** | `--primary: #003566` | Topbar, Button Backgrounds, Card Accents, Titles |
| **Primary Dark** | `--primary-dark: #00224d` | Hover states, Footer background (`#0f172a`) |
| **Primary Light** | `--primary-light: #e6f0ff` | Light badge fills, Circular icon backgrounds |
| **Accent Teal** | `--accent: #0d9488` | Active link underlines, Designation badges, Secondary buttons |
| **Gold Highlight** | `#FFBF00` | Navbar bottom border, Hero badges, CTA buttons (`.btn-accent`) |
| **Background Body** | `#fffff0` | Warm ivory page background |
| **Typography** | `'Nunito'` (Headings), `'Inter'` (Body) | Crisp hierarchy, high contrast readability |
| **Glassmorphism** | `rgba(255,255,255,0.85)` + `blur(16px)` | Floating sticky navbar (`.navbar-modern`) |

---

## 📱 2. SCREEN-BY-SCREEN VISUAL AUDIT

### Screen 1: Public Home Page (`/`)
- **Header Stack**: Topbar (`#003566`) with helpline & social links + Yellow announcement ticker + Glassmorphic sticky navbar.
- **Hero Section**: High-contrast gradient background with 900-weight Nunito title, pill badges, and 3 primary action buttons.
- **Live Statistics**: 4 white `.stat-card` containers with hover lift animation, circular icon badges (`.icon-circle`), and counter metrics.
- **Executive Leadership**: Leader profile cards with rounded avatar photos, teal designation badges (`#0d9488`), and college tags.
- **Upcoming Events & Notices**: Split layout with event cards and notice list items.
- **Affiliations Marquee**: CSS CSS ticker showing Panjab University, MCM DAV, DAV 10, PEC, PGGC 11, SD College, GMCH 32.

### Screen 2: Online Student Registration Form (`/membership/register`)
- **Header**: Gold badge indicator + card title.
- **Form Sections**:
  1. Personal Details (Name, Gender, DOB, Phone, Email, Blood Group)
  2. Academic Details (Searchable college select via Choices.js, Course, Dept, Year, Roll No)
  3. Address & Emergency Contact (Manipur address, Chandigarh PG address, Emergency phone)
  4. Photograph Upload (FilePond drag-and-drop file uploader)
- **Submit Action**: Gold CTA button submitting registration and directing to Digital ID Card view.

### Screen 3: Digital Student Membership ID Card (`/membership/id-card/{id}`)
- **Dimensions**: `350px x 540px` rounded ID card wrapper with 3D drop shadow.
- **Header**: Gradient background (`#003566` to `#0d9488`), gold border, KSO logo.
- **Photo Frame**: Centered 105px x 115px photo box with navy border.
- **Member Info**: Student Name in Nunito 800 bold, Membership ID badge (`KSO-CHD-2026-XXXX`).
- **Details Table**: College, Course, Year, Blood Group (in bold red), Emergency Contact.
- **Footer**: Validity date (`2027-06-30`), KSO GHQ seal text, and dynamic QR Code.

### Screen 4: Public Student ID Verification Portal (`/membership/verify`)
- **Search Form**: Shield icon header + text input accepting 16-character Membership ID.
- **Verification Result Card**:
  - If Verified: Green border card with student photo, active status badge (`APPROVED`), college details, and validity.
  - If Unverified: Red alert card indicating no matching record found.

### Screen 5: Member Student Portal Dashboard (`/membership/portal/dashboard`)
- **Layout**: Split container.
  - Left Column: Logged-in student's Digital ID Card with one-click print button (`window.print()`).
  - Right Column: Student profile table and latest organization notices.

### Screen 6: Events & FullCalendar Community Grid (`/events`)
- **FullCalendar 6 Grid**: Interactive monthly calendar displaying event dots color-coded by category (Cultural = Navy, Sports = Teal, Social = Red).
- **Category Filter Bar**: Filter buttons for All, Cultural, Sports, Academic, Social Service.
- **Event Cards**: Poster image, venue, date, time, and "RSVP / Event Pass" action buttons.

### Screen 7: Event RSVP & Entry Pass (`/events/ticket/{ticketCode}`)
- **Event Show View**: Detailed description, venue map info, and RSVP form.
- **Printable Event Entry Pass**: Entry pass featuring attendee name, college, event date, venue, unique pass code (`TICKET-2026-X9Y2`), and QR code.

### Screen 8: Donation & Welfare Support Portal (`/donations`)
- **Multi-Gateway Payment Checkout**:
  - UPI QR Code box with official UPI ID (`ksochandigarh@upi`).
  - Credit/Debit Card Checkout via Stripe.js SDK.
  - PayPal Payment Buttons.
  - Razorpay NetBanking modal.
- **Preset Amount Selector**: Quick amount buttons (₹100, ₹500, ₹1000, ₹2500, ₹5000) and recent contributor list.

### Screen 9: Admin CMS Dashboard (`/admin/dashboard`)
- **Top Dark Header Bar**: Admin user greeting, quick link to public site, logout button.
- **KPI Metric Cards**: Total Members, Pending Approvals, Total Events, Total Donations Collected.
- **ApexCharts Analytics Graphs**:
  1. *Monthly Student Registrations Trend* (Area Chart)
  2. *Monthly Donations Collection* (Column Bar Chart)
- **Recent Activity**: Tables for Recent Applications and Inquiries.

### Screen 10: Admin Membership Management & Approval Desk (`/admin/members`)
- **Toolbar**: Search box + Status filter dropdown + Export CSV button.
- **Member Directory Table**: Photos, IDs, names, colleges, status badges.
- **Action Buttons**:
  - `Approve`: Updates status, sets approval date, sends `MemberApprovedMail`.
  - `Reject`: Marks application as rejected.
  - `View Profile`: `/admin/members/{id}` detailed view.
  - `Edit Member`: `/admin/members/{id}/edit` full editing form.
  - `Delete`: SweetAlert2 confirmed deletion.

### Screen 11: Admin Financial Management & General Ledger (`/admin/financial`)
- **Summary Cards**: Total Income, Total Expenses, Net Reserve Balance.
- **Voucher Entry Form**: Double-entry transaction logging with category, payment method, payee/payer name, narration, and bill attachment upload.
- **Voucher Ledger Table**: Complete audit history of financial transactions.

### Screen 12: Admin Page Builder, Events, News, Gallery, Medical & Audit Trail
- **Page Builder (`/admin/pages`)**: CKEditor 5 rich text editor for dynamic pages.
- **Medical Emergency Claims (`/admin/medical`)**: Student health assistance desk for PGIMER & GMCH-32.
- **Audit Logs (`/admin/audit`)**: Security log with user IDs, IP addresses, and timestamps.

---

## 🛠️ 3. RESPONSIVE BREAKPOINT TEST SUMMARY

| Breakpoint | Devices Tested | Visual Behavior | Status |
|------------|----------------|-----------------|--------|
| **Mobile (`< 576px`)** | iPhone, Android | Glass navbar collapses to hamburger menu; hero stack reorders to image top + text bottom; ID card scales to 100% width; tables enable horizontal scroll | ✅ PASS |
| **Tablet (`576px - 991px`)** | iPad, Tablet | Stat cards stack 2x2; admin metric cards stack 2x2; charts resize dynamically | ✅ PASS |
| **Desktop (`> 992px`)** | Laptop, Monitor | Glassmorphism blur navbar active; dropdowns expand on hover; split layouts display side-by-side | ✅ PASS |
