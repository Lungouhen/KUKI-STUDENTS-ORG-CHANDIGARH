# Remediation Report — KSO Chandigarh

Everything raised in the deployment-readiness and JS/CSS audits has been fixed,
plus a professional redesign of the admin sidebar.

**One item could not be completed in this environment — see "Still outstanding".**

---

## Verification

| Check | Method | Result |
|---|---|---|
| PHP syntax | `token_get_all(TOKEN_PARSE)`, 98 non-Blade files | 0 errors |
| Blade compile | real `Illuminate\View\Compilers\BladeCompiler` (v11.44.2), 65 views | 0 errors |
| Route names / views / controller methods | static resolution incl. `->only([...])` | all resolve |
| Inline JS | acorn 8, ES2022, 10 blocks | 0 parse failures |
| CSS | PostCSS 8 parse | OK — 236 rules, 806 declarations |
| QR output | 213 payloads → rasterised → decoded with `jsQR` | **213/213 decode correctly** |
| Icons | every `fa-*` class cross-referenced against the stylesheet | 117/117 resolve |
| Repo's Blade audit | `tests/playwright_audit.js` | **65/65** |
| Repo's reference audit | `tests/referencing_check.py` | zero errors |
| Front-end build | `npm ci && npm run build` (Vite 5) | succeeds |

---

## Blockers fixed

**B1 — `app/Http/Controllers/Controller.php` was missing.** All 29 controllers
extend it. Added the abstract base class; every request previously fatalled.

**B2 — `storage/` and `bootstrap/cache/` were never committed.** Created the full
tree with `.gitkeep` files and rewrote `.gitignore` so the directories are tracked
while their runtime contents stay ignored (verified with `git check-ignore`).
`composer install` previously failed at `package:discover`.

**B4 — invalid hardcoded `APP_KEY`.** The committed fallback base64-decoded to
35 bytes; AES-256-CBC requires exactly 32, so an unset `APP_KEY` threw at boot.
Removed the fallback — the key must now come from the environment.

Also rebuilt `config/app.php` to defer to `ServiceProvider::defaultProviders()`
and `Facade::defaultAliases()` instead of a hand-pinned list that had drifted
(it was missing `ConcurrencyServiceProvider` and omitted `aliases` entirely,
leaving the `Str::` facade used in 3 Blade files resolving only by accident).

## High-severity fixes

**H1/H2 — resource routes vs. reality.** `Route::resource` registered 9 actions
that did not exist. Scoped the routes with `->only([...])`, implemented
`ProjectController::edit/update/destroy`, `ElectionController::destroy`, and
added the missing `partners/edit` and `projects/edit` views. `partners.edit` was
linked from the live partners list and returned a 500.

**H3/H4 — string IDs written into auto-increment integer primary keys.**
`projects.id` and `donations.id` are `BIGINT`, and `beneficiaries.project_id` is
a foreign key onto the former, so the key could not simply become a string.
Added a `project_code` column via migration, moved `PROJ-2026-0001` there, and
derived the sequence from the highest existing code rather than a row count
(a count breaks after any deletion). Removed the bogus `'id'` assignment in
`PaymentGatewayService`, which was also silently dropped as non-fillable.

**H5 — dead QR endpoint.** `chart.googleapis.com` was shut down by Google in
March 2024, so the ID-card QR was a broken image. Rather than swap in another
third-party endpoint, I wrote a dependency-free encoder (`app/Support/QrCode.php`)
that renders inline SVG server-side.

> Two real bugs surfaced while testing it and were fixed: the capacity table
> confused data codewords with ECC codewords, and versions ≥ 7 omitted the
> mandatory 18-bit version-information block. Both were caught by round-tripping
> 213 payloads through an independent decoder — all 10 versions now pass.

Also replaced the fake `fa-qrcode` glyphs on the ID-card preview and event ticket
with genuine scannable codes.

**H6 — the member portal had no authentication.** Login required only a
membership ID, which is sequential and printed on every card, so anyone could
enumerate IDs and read another student's DOB, address, phone, emergency contacts
and medical claims. Added date-of-birth as a second factor, rate limiting
(5 attempts / 15 min per identifier+IP), generic failure messages that do not
disclose whether an ID exists, an approved-status check, and session
regeneration to prevent fixation.

**H7 — public API leaked the full member record.** `/api/verify/{id}` returned
the entire Eloquent model. It now returns only name, institution, type, status
and validity.

**H8 — logout redirected to a POST-only route** (405). Now redirects to the GET
login screen and fully invalidates the session.

## JS & CSS fixes

**J1 — Alpine scope bug (broken feature).** In `membership/register.blade.php`
`x-data` sat on a banner div closing at line 12, while `x-model` (line 98) and
`x-show` (line 104) were outside it — so choosing "Family" membership never
revealed the dependants field. Moved the scope to wrap the form; verified by
div-balancing that both directives are now inside it.

**J2/J3 — flash of unstyled content.** Added `[x-cloak]{display:none!important}`
(39 `x-show` elements were painting visible then vanishing) and a tiny blocking
head script that applies the saved theme before first paint.

**J6 — dashboard charts showed invented data.** Both series were hardcoded
arrays. `DashboardController` now computes a real trailing-12-month window for
registrations and donations, aggregated in PHP so it works on both SQLite and
MySQL. Added null guards (J7) and empty-state messages.

**J4 — `custom.css` downloaded twice** (once directly, once inlined by Vite).
The direct `<link>` is now only a fallback for when no build exists.

**J9 — dark mode leaks.** `.navbar-modern .dropdown-menu` was hardcoded `#fff`,
and the mobile navbar used `background:#fffff0 !important`, which defeated the
theme and kept the mobile header light on a dark page. Both now use theme tokens
(added `--nav-bg-solid` for surfaces without a backdrop-filter).

**J10 — render-blocking Google Fonts `@import`** replaced with `preconnect` plus
a non-blocking `<link>` (with a `<noscript>` fallback).

**J11 — axios had no CSRF token.** Added `<meta name="csrf-token">` to both
layouts and wired `X-CSRF-TOKEN` in `bootstrap.js`; POSTs would have 419'd.

**M1/M2 — stub libraries replaced with the real thing.**

| Was | Now |
|---|---|
| FontAwesome: 2.6 KB emoji hack, 63 of 118 icons | **real FA 6.7.2** + webfonts — 117/117 resolve |
| CKEditor: 234-byte fake returning the textarea value | **real CKEditor 5** (1.3 MB), loaded on demand |
| FullCalendar: 839-byte fake rendering a card list | **real FullCalendar 6.1.15** |

`fa-calendar-pen` was swapped for `fa-calendar-day` (the former is Pro-only).

**~1 MB of dead payload removed.** Choices.js and FilePond were loaded on every
page but never initialised anywhere — deleted. ApexCharts (796 KB) now loads only
on the dashboard, FullCalendar only on the events page, CKEditor only where a
`[data-richtext]` textarea exists. `package.json` dependencies were also
vestigial (assets are self-hosted, not bundled); only `axios` is a real build input.

## Production hygiene

- `.env.example` now defaults to safe values and documents `APP_KEY` generation.
- **Removed the published default password.** `AdminUserSeeder` reads
  `ADMIN_PASSWORD`, generates a random one in dev (printed once), and **refuses
  to seed an admin in production** without an explicit password. README updated.
- `deployment/nginx.conf` rewritten: TLS + HSTS, HTTP→HTTPS redirect, ACME
  location, hardened headers, `.php` execution denied under `/storage/`
  (user-uploaded photos and medical documents), and asset caching.
- `phpunit.xml` added — the suite previously could not run at all. Uses in-memory
  SQLite so tests never touch `database/database.sqlite`.
- `kso:deploy` now creates and permission-checks runtime directories before
  migrating, and creates the SQLite file if absent.
- Fixed two **false positives** in the repo's own audit scripts: `@hasSection`
  closes with `@endif`, and `referencing_check.py` used a hardcoded route
  allowlist that had drifted — it now parses `routes/web.php`, including group
  prefixes and `Route::resource` with `->only()`.

---

## Admin sidebar redesign

The previous sidebar had genuine defects: the collapse button toggled
`w-sidebar`/`w-icon-sidebar`, **neither of which was ever defined in CSS** (width
was hardcoded inline at 260px, so collapsing did nothing); "Donors" and
"Donations" pointed at the same route; there was a dead `href="#"`; and all seven
Content items highlighted simultaneously because they share one route name.

**Structure** — 33 items in 7 logical groups (Dashboard, Membership, Content,
Programmes, Finance, Governance, System), rendered from a single declarative PHP
array instead of ~40 hand-written `<li>` blocks, so active-state logic stays
consistent and the menu is editable in one place.

**Accurate active state** — items with query strings use exact URL matching,
others use route patterns. Verified: exactly one item highlights per URL.

**Working collapse** — a real 268px ↔ 76px icon rail, persisted to
`localStorage` and applied pre-paint so it does not flicker. In the rail, labels
become hover tooltips and badges shrink to dots.

**Proper mobile behaviour** — an off-canvas drawer with a backdrop, rather than a
cramped icon rail on a phone.

**Also added** — a live menu filter that hides empty groups; unread/pending count
badges (via a view composer bound only to the admin layout, guarded with
`Schema::hasTable` so it cannot fatal before migrations); a user block with
avatar, role and sign-out; sticky topbar with optional subtitle; a global
validation-error summary; `aria-current`, focus-visible rings,
`prefers-reduced-motion` support, and print styles.

All 27 sidebar routes were verified to resolve, and every `admin-*` class was
verified to have a matching CSS rule. The 9 Alpine bindings map 1:1 to the 9
properties defined in `adminShell()`.

---

## Still outstanding

**`composer.lock` is still not committed (B3).** Packagist and
`getcomposer.org` are firewalled in this environment, so I could not run
`composer install` to generate it. This must be done before deploying:

```bash
composer install          # generates composer.lock
git add composer.lock && git commit -m "Add composer.lock"
```

**Nothing here replaces a real runtime smoke test.** PHP is not installed in this
sandbox, so I verified by parsing, compiling Blade with the actual framework
compiler, and decoding real QR output — but the application was never booted.
Before going live, run:

```bash
composer install
php artisan key:generate
php artisan kso:deploy --seed
php artisan test
php artisan serve
```

and click through the admin modules, the membership registration form (confirm
the Family dependants field appears), the portal login, and an ID card.

Self-hosting the Google Fonts files is also still worth doing — `fonts.gstatic.com`
was unreachable here, so the fonts remain a (now non-blocking) external request.
