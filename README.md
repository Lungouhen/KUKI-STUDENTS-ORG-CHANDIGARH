# KSO CHANDIGARH — NGO WEBSITE, MEMBERSHIP MANAGEMENT & CMS PLATFORM

![KSO Chandigarh Logo](public/images/kso-logo.jpg)

An enterprise-grade, full-stack **Laravel 12** web application for the **Kuki Students' Organisation (KSO) Chandigarh**, serving student scholars across Panjab University, MCM DAV, DAV 10, PEC, PGGC 11, SD College, and GMCH 32.

---

## 🌟 CORE MVP FEATURES

### 1. 🌐 Public NGO Website
- **Modern Glass Navbar & Topbar**: Translucent backdrop-filter navbar with yellow accent border (`#FFBF00`), hover dropdowns, social links, and 24/7 student helpline.
- **Announcement Ticker**: Real-time scrolling notice banner.
- **Hero Section & Stats Bar**: Gradient hero with custom typography, CTAs, and live counters (Active Members, Colleges Covered, Annual Events, Emergency Cell).
- **Executive Body Directory**: Profiles of office bearers (President, Vice President, General Secretary, etc.) with photos and contact info.
- **Events & News Calendar**: Filterable events (Cultural, Sports, Academic, Social Service) and notice archive.
- **Photo Gallery**: Filterable photo gallery of cultural nights (Chavang Kut), sports meets, and blood donation drives.
- **Donation & Welfare Support**: Interactive welfare cause selector, preset amount buttons, UPI QR code display (`ksochandigarh@upi`), donor records, and recent contributors list.
- **Emergency Medical Relief Desk**: Direct helpline cards for hospital emergencies at PGIMER and GMCH Sector 32.

### 2. 🪪 Membership Management System
- **Online Student Registration**: Collects full student details, Chandigarh college/department, course, year, permanent Manipur address, current PG/Hostel address, emergency contact, and photo upload.
- **Digital Membership ID Card**: Generates an official, printable KSO Student ID Card featuring student photo, unique Membership ID (e.g. `KSO-CHD-2026-0001`), official seal, validity date, and dynamic verification QR code.
- **Public ID Verification Tool**: Online ID lookup tool for public and institutional verification.
- **Student Member Portal**: Login with Membership ID or Email to access digital ID card, application status, student resources, and request official certificates.
- **Certificate Generation & Distribution**: Members can request character, bonafide, membership, participation, volunteer-service, appreciation, achievement, completion, and other certificates. Admins review and issue or reject requests; issued certificates are printable/saveable as PDF from the member portal and verifiable by certificate number. Revoked certificates remain verifiable as revoked.
- **Offline Membership Forms**: Admins can share the public registration link or build a section-based blank form, print it, save it as PDF from the browser print dialog, or download a standalone HTML copy for offline printing. Paper applications must be entered and reviewed through the existing admin member workflow; the form tool does not create or approve member records.

### 3. 🛡️ CMS & Admin Management Panel
- **Admin Dashboard**: Overview metrics (Total Members, Pending Approvals, Total Events, Total Donations).
- **Membership Management**: 1-click **Approve**, **Reject**, **Delete**, **View/Print ID Card**, and **CSV Export**.
- **Financial Ledger & Accounts**: Transaction vouchers, account balances, and income vs expense reports; admins can export the filtered server-generated CSV or open a server-rendered print report and save it as PDF from the browser.
- **Content Management**:
  - Events CMS (Create, Edit, Delete events)
  - Announcements & News CMS
  - Executive Body Directory CMS
  - Gallery Photo Uploader CMS
  - Dynamic Page Builder (`/page/{slug}`)
  - Draft-first page editing, publication filters, and admin-only previews before publication; existing public page URLs stay stable when titles change.
  - Built-in page layouts: standard article, landing page, notice, and wide content. Select a layout when creating or editing a page; existing pages default to the standard layout.
  - FAQ Manager
  - Testimonial Manager
  - Medical Emergency Claims Desk
  - System Audit Trail Logs

---

## 🚀 QUICK START & DEPLOYMENT

### Prerequisites
- PHP >= 8.3 (the included Nginx configuration uses PHP 8.5-FPM)
- Composer
- Node.js >= 22.12.0 and npm (to build frontend assets)
- SQLite / MySQL

### Local Setup Instructions

```bash
# 1. Clone the repository
git clone https://github.com/Lungouhen/KUKI-STUDENTS-ORG-CHANDIGARH.git
cd KUKI-STUDENTS-ORG-CHANDIGARH

# 2. Install dependencies
composer install

# 3. Environment configuration
cp .env.example .env
php artisan key:generate

# 4. Run database migrations
php artisan kso:deploy

# 5. Create the first admin account (password is entered securely at the prompt)
php artisan kso:admin:create admin@example.org --name="KSO Administrator"

# 6. Build frontend assets
npm ci
npm run build

# 7. Start the development server
php artisan serve
```

Use `php artisan kso:deploy --seed` only when you intentionally want the project's sample data. Seeding does not create or reset admin accounts. To add another admin, run `php artisan kso:admin:create` with that person's unique email. Existing accounts are never promoted, renamed, or assigned a new password by this command or by seeding.

Scheduled CMS pages, news, events, and reusable content are published by Laravel's scheduler. Configure the host to run `php artisan schedule:run` once per minute (for example, with the standard Laravel scheduler cron entry) for scheduled publication to take effect.

## 🧪 Testing

Run the automated PHP feature tests with `php artisan test`. They use an isolated in-memory SQLite database. Build assets with `npm ci && npm run build`.

The committed audit scripts perform static checks only; they are not substitutes for the PHPUnit feature tests or a security/accessibility certification.

---

## 🗺️ ROUTE MAP

| Route | Method | Description |
|-------|--------|-------------|
| `/` | GET | Public Home Page |
| `/about` | GET | About Us & Executive Body |
| `/membership/register` | GET/POST | Online Student Membership Application |
| `/membership/verify` | GET/POST | Public Student ID Verification Tool |
| `/members/portal` | GET/POST | Student Portal Login & Dashboard |
| `/members/portal/documents` | POST | Request an official member certificate |
| `/members/portal/documents/{id}` | GET | View and print an issued certificate (member only) |
| `/documents/verify/{certificateNumber}` | GET | Public certificate status verification |
| `/admin/member-documents` | GET | Review, issue, reject, or revoke member documents |
| `/admin/member-document-templates` | GET | Preview and publish immutable certificate template versions |

### Certificate templates and batches

Administrators can create a new template version for each supported document type using plain-text statements and the documented placeholders (`{{member_name}}`, `{{member_id}}`, `{{institution}}`, `{{course}}`, `{{certificate_details}}`, `{{issued_date}}`, `{{issuer}}`, `{{certificate_number}}`, and `{{verification_url}}`). HTML is rejected and rendered values are escaped. Each issued document snapshots the template output and member fields, so later template/profile changes do not rewrite it.

The document register supports exact certificate/member lookup, name/type/status/date/batch filters, individual preview-before-issue, and bulk generation from member IDs or an institution/course cohort. Batches are limited to 500 members, process in chunks of 25, keep per-member issued/skipped/failed outcomes, and use a unique idempotency key; admins can retry failed outcomes without duplicating issued documents. Bulk certificates have an admin-only print view. Members receive an availability email with a portal sign-in link (no certificate attachment); delivery state is recorded per document.

Issued certificates remain privately available through the member portal as printable HTML / browser Save as PDF. A human-readable certificate number and QR code link to the throttled, no-store verification register, which reports valid/revoked/not-found and masks the member ID. Verification is register-backed and is not a digital signature.
| `/members/id-card/{id}` | GET | Official Digital ID Card View & Print |
| `/events` | GET | Events & News Calendar |
| `/gallery` | GET | Photo Gallery |
| `/donations` | GET/POST | Donation Portal & Records |
| `/contact` | GET/POST | Contact Form & Emergency Helplines |
| `/admin/login` | GET/POST | CMS Admin Authentication |
| `/admin/dashboard` | GET | CMS Admin Dashboard |
| `/admin/members` | GET | Membership Management & Approval |
| `/admin/membership-forms` | GET | Admin form builder, online link sharing, print preview, and offline HTML download |
| `/admin/members/export/csv` | GET | Export Member Directory to CSV |
| `/admin/financial` | GET/POST | Financial Ledger & Transaction Vouchers |
| `/admin/financial/print` | GET | Print/Save as PDF of a filtered server-rendered financial report |
| `/admin/financial/export` | GET | Stream a filtered CSV financial report |
| `/admin/pages` | GET/POST | Dynamic Page Builder |
| `/admin/medical` | GET/POST | Emergency Medical Claims Desk |
| `/admin/audit` | GET | System Audit Logs |

---

## 📂 DIRECTORY STRUCTURE

```
KUKI-STUDENTS-ORG-CHANDIGARH/
├── app/
│   ├── Console/Commands/KsoDeployCommand.php
│   ├── Http/
│   │   ├── Controllers/       # Public & Admin Controllers
│   │   └── Middleware/        # Admin Middleware
│   ├── Mail/                  # Mailable Notifications
│   ├── Models/                # Eloquent Models (Member, Event, Transaction, etc.)
│   └── Services/              # Razorpay / Payment Gateway Service
├── config/                    # Configuration Files
├── database/
│   ├── migrations/            # Table Schemas
│   └── seeders/               # Pre-populated Seed Data
├── deployment/                # Nginx Server Block Configuration
├── public/
│   ├── css/custom.css         # Complete CSS Styling
│   └── images/                # Asset Images & Placeholders
├── resources/views/           # Blade View Templates
├── routes/
│   ├── web.php                # Web Application Routes
│   └── api.php                # API Routes for QR Verification
└── tests/                     # Automated PHPUnit Feature Tests
```

---

## 📜 LICENSE
This project is open-source software licensed under the [MIT License](LICENSE).
