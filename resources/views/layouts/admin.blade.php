<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KSO Admin CMS Control Panel')</title>

    {{-- Restore theme + sidebar state before first paint to avoid a flash. --}}
    <script>
        (function () {
            try {
                if (localStorage.getItem('theme') === 'dark') {
                    document.documentElement.setAttribute('data-bs-theme', 'dark');
                }
                if (localStorage.getItem('kso.sidebar') === 'collapsed') {
                    document.documentElement.classList.add('sidebar-is-collapsed');
                }
            } catch (e) { /* storage unavailable */ }
        })();
    </script>

    <!-- Web fonts: preconnect + non-blocking load -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" media="print" onload="this.media='all'"
          href="https://fonts.googleapis.com/css2?family=Nunito:wght@700;800;900&family=Inter:wght@300;400;500;600;700&display=swap">

    <!-- Local Bootstrap 5 CSS -->
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Local FontAwesome 6 CSS -->
    <link href="{{ asset('vendor/fontawesome/all.min.css') }}" rel="stylesheet">
    <!-- Local SweetAlert2 CSS -->
    <link href="{{ asset('vendor/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">

    {{-- custom.css is bundled by Vite via resources/css/app.css; only link it
         directly when there is no build, otherwise it loads twice. --}}
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @endif

    <!-- Local Alpine.js -->
    <script defer src="{{ asset('vendor/alpine/alpine.min.js') }}"></script>
    <!-- Local SweetAlert2 JS -->
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>

    @stack('styles')
</head>
<body
    x-data="adminShell()"
    x-init="init()"
    :class="{ 'sidebar-collapsed': collapsed, 'sidebar-mobile-open': mobileOpen }"
    :data-bs-theme="darkMode ? 'dark' : 'light'"
    class="admin-body"
>

    {{-- Backdrop for the off-canvas sidebar on small screens --}}
    <div class="admin-backdrop" @click="mobileOpen = false" x-show="mobileOpen" x-cloak></div>

    <aside class="admin-sidebar" aria-label="Admin navigation">

        <!-- ─── Brand ─── -->
        <div class="admin-sidebar__brand">
            <a href="{{ route('admin.dashboard') }}" class="admin-brand-link">
                <span class="admin-brand-mark">
                    <img src="{{ asset('images/kso-logo.jpg') }}" alt=""
                         onerror="this.replaceWith(Object.assign(document.createElement('span'),{className:'admin-brand-fallback',textContent:'K'}))">
                </span>
                <span class="admin-brand-text">
                    <span class="admin-brand-title">KSO Chandigarh</span>
                    <span class="admin-brand-sub">Admin Console</span>
                </span>
            </a>
            <button type="button" class="admin-sidebar__close d-lg-none"
                    @click="mobileOpen = false" aria-label="Close navigation">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- ─── Quick filter ─── -->
        <div class="admin-sidebar__search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="search" x-model="query" placeholder="Search menu…"
                   aria-label="Filter navigation" spellcheck="false">
            <button type="button" x-show="query" @click="query = ''" x-cloak aria-label="Clear search">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- ─── Navigation ─── -->
        @php
            /**
             * Single source of truth for the sidebar.
             *
             * Rendering from a structure (rather than ~40 hand-written <li>s)
             * keeps active-state logic consistent and makes the menu editable in
             * one place. `match` is the route pattern used for highlighting;
             * `exact` compares the full URL so query-string views (e.g. the
             * Content module's ?type=) do not all light up at once.
             */
            $nav = [
                [
                    'group' => null,
                    'items' => [
                        ['label' => 'Dashboard', 'icon' => 'fa-gauge-high', 'url' => route('admin.dashboard'), 'match' => 'admin.dashboard'],
                    ],
                ],
                [
                    'group' => 'Membership',
                    'items' => [
                        ['label' => 'All Members', 'icon' => 'fa-users', 'url' => route('admin.members.index'), 'match' => 'admin.members.index', 'exact' => route('admin.members.index')],
                        ['label' => 'Pending Requests', 'icon' => 'fa-user-clock', 'url' => route('admin.members.index', ['status' => 'Pending']), 'exact' => route('admin.members.index', ['status' => 'Pending']), 'badge' => $pendingMemberCount ?? null],
                        ['label' => 'Membership Fees', 'icon' => 'fa-receipt', 'url' => route('admin.members.fees'), 'match' => 'admin.members.fees'],
                        ['label' => 'Committee & Roles', 'icon' => 'fa-user-tie', 'url' => route('admin.committee.index'), 'match' => 'admin.committee*'],
                    ],
                ],
                [
                    'group' => 'Content',
                    'items' => [
                        ['label' => 'Homepage Slider', 'icon' => 'fa-images', 'url' => route('admin.content.index', ['type' => 'slider']), 'exact' => route('admin.content.index', ['type' => 'slider'])],
                        ['label' => 'Pages', 'icon' => 'fa-file-lines', 'url' => route('admin.pages.index'), 'match' => 'admin.pages*'],
                        ['label' => 'News', 'icon' => 'fa-newspaper', 'url' => route('admin.news.index'), 'match' => 'admin.news*'],
                        ['label' => 'Notices', 'icon' => 'fa-bullhorn', 'url' => route('admin.content.index', ['type' => 'notice']), 'exact' => route('admin.content.index', ['type' => 'notice'])],
                        ['label' => 'Gallery', 'icon' => 'fa-image', 'url' => route('admin.gallery.index'), 'match' => 'admin.gallery*'],
                        ['label' => 'Achievements', 'icon' => 'fa-trophy', 'url' => route('admin.content.index', ['type' => 'achievement']), 'exact' => route('admin.content.index', ['type' => 'achievement'])],
                        ['label' => 'Certificates', 'icon' => 'fa-certificate', 'url' => route('admin.content.index', ['type' => 'certificate']), 'exact' => route('admin.content.index', ['type' => 'certificate'])],
                        ['label' => 'Policies', 'icon' => 'fa-shield-halved', 'url' => route('admin.content.index', ['type' => 'policy']), 'exact' => route('admin.content.index', ['type' => 'policy'])],
                        ['label' => 'Careers', 'icon' => 'fa-briefcase', 'url' => route('admin.content.index', ['type' => 'career']), 'exact' => route('admin.content.index', ['type' => 'career'])],
                        ['label' => 'FAQs', 'icon' => 'fa-circle-question', 'url' => route('admin.faqs.index'), 'match' => 'admin.faqs*'],
                        ['label' => 'Testimonials', 'icon' => 'fa-quote-left', 'url' => route('admin.testimonials.index'), 'match' => 'admin.testimonials*'],
                    ],
                ],
                [
                    'group' => 'Programmes',
                    'items' => [
                        ['label' => 'Projects', 'icon' => 'fa-diagram-project', 'url' => route('admin.projects.index'), 'match' => 'admin.projects*'],
                        ['label' => 'Campaigns', 'icon' => 'fa-bullseye', 'url' => route('admin.content.index', ['type' => 'campaign']), 'exact' => route('admin.content.index', ['type' => 'campaign'])],
                        ['label' => 'Beneficiaries', 'icon' => 'fa-hand-holding-hand', 'url' => route('admin.beneficiaries.index'), 'match' => 'admin.beneficiaries*'],
                        ['label' => 'Medical Relief', 'icon' => 'fa-kit-medical', 'url' => route('admin.medical.index'), 'match' => 'admin.medical*'],
                        ['label' => 'Partners', 'icon' => 'fa-handshake', 'url' => route('admin.partners.index'), 'match' => 'admin.partners*'],
                    ],
                ],
                [
                    'group' => 'Finance',
                    'items' => [
                        ['label' => 'Donations', 'icon' => 'fa-hand-holding-heart', 'url' => route('admin.donations.index'), 'match' => 'admin.donations*'],
                        ['label' => 'Ledger & Reports', 'icon' => 'fa-chart-pie', 'url' => route('admin.financial.index'), 'exact' => route('admin.financial.index')],
                        ['label' => 'Expenses', 'icon' => 'fa-file-invoice-dollar', 'url' => route('admin.financial.index', ['type' => 'Expense']), 'exact' => route('admin.financial.index', ['type' => 'Expense'])],
                    ],
                ],
                [
                    'group' => 'Governance',
                    'items' => [
                        ['label' => 'Elections', 'icon' => 'fa-check-to-slot', 'url' => route('admin.elections.index'), 'match' => 'admin.elections*'],
                        ['label' => 'Executive Terms', 'icon' => 'fa-calendar-days', 'url' => route('admin.terms.index'), 'match' => 'admin.terms*'],
                        ['label' => 'Messages', 'icon' => 'fa-envelope', 'url' => route('admin.messages.index'), 'match' => 'admin.messages*', 'badge' => $unreadMessageCount ?? null],
                        ['label' => 'Audit Trail', 'icon' => 'fa-clipboard-check', 'url' => route('admin.audit.index'), 'match' => 'admin.audit*'],
                    ],
                ],
                [
                    'group' => 'System',
                    'items' => [
                        ['label' => 'Admin Users', 'icon' => 'fa-user-shield', 'url' => route('admin.users.index'), 'match' => 'admin.users*'],
                        ['label' => 'Organization', 'icon' => 'fa-sitemap', 'url' => route('admin.settings.index'), 'match' => 'admin.settings.index'],
                        ['label' => 'Email / SMTP', 'icon' => 'fa-envelope-circle-check', 'url' => route('admin.settings.smtp'), 'match' => 'admin.settings.smtp'],
                        ['label' => 'Payment Gateways', 'icon' => 'fa-credit-card', 'url' => route('admin.settings.gateways'), 'match' => 'admin.settings.gateways'],
                        ['label' => 'Integrations', 'icon' => 'fa-plug', 'url' => route('admin.settings.integrations'), 'match' => 'admin.settings.integrations'],
                    ],
                ],
            ];
        @endphp

        <nav class="admin-sidebar__nav" x-ref="nav">
            @foreach($nav as $section)
                <div class="admin-nav-section" x-show="sectionVisible($el)">
                    @if($section['group'])
                        <p class="admin-nav-heading"><span>{{ $section['group'] }}</span></p>
                    @endif

                    <ul class="admin-nav-list">
                        @foreach($section['items'] as $item)
                            @php
                                $isActive = isset($item['exact'])
                                    ? request()->fullUrlIs($item['exact'])
                                    : (isset($item['match']) && request()->routeIs($item['match']));
                            @endphp
                            <li x-show="matches('{{ Str::lower($item['label']) }}')">
                                <a href="{{ $item['url'] }}"
                                   class="admin-nav-link {{ $isActive ? 'is-active' : '' }}"
                                   @if($isActive) aria-current="page" @endif
                                   data-label="{{ $item['label'] }}">
                                    <i class="fa-solid {{ $item['icon'] }}" aria-hidden="true"></i>
                                    <span class="admin-nav-label">{{ $item['label'] }}</span>
                                    @if(!empty($item['badge']))
                                        <span class="admin-nav-badge">{{ $item['badge'] > 99 ? '99+' : $item['badge'] }}</span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            <p class="admin-nav-empty" x-show="query && !$refs.nav.querySelector('li:not([style*=none])')" x-cloak>
                No menu item matches “<span x-text="query"></span>”.
            </p>
        </nav>

        <!-- ─── Footer / account ─── -->
        <div class="admin-sidebar__footer">
            <div class="admin-user">
                <span class="admin-user__avatar">{{ Str::upper(Str::substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                <span class="admin-user__meta">
                    <span class="admin-user__name">{{ auth()->user()->name ?? 'Administrator' }}</span>
                    <span class="admin-user__role">{{ Str::title(str_replace('_', ' ', auth()->user()->role ?? 'admin')) }}</span>
                </span>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST" class="admin-user__logout">
                @csrf
                <button type="submit" title="Sign out" aria-label="Sign out">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- ─── Main column ─── -->
    <div class="admin-main">

        <header class="admin-topbar">
            <div class="admin-topbar__left">
                <button type="button" class="admin-icon-btn d-lg-none" @click="mobileOpen = true" aria-label="Open navigation">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <button type="button" class="admin-icon-btn d-none d-lg-inline-flex" @click="toggleCollapsed()"
                        :aria-label="collapsed ? 'Expand sidebar' : 'Collapse sidebar'"
                        :title="collapsed ? 'Expand sidebar' : 'Collapse sidebar'">
                    <i class="fa-solid" :class="collapsed ? 'fa-angles-right' : 'fa-angles-left'"></i>
                </button>
                <div class="admin-topbar__titles">
                    <h1 class="admin-topbar__title">@yield('title', 'Admin Panel')</h1>
                    @hasSection('subtitle')
                        <p class="admin-topbar__subtitle">@yield('subtitle')</p>
                    @endif
                </div>
            </div>

            <div class="admin-topbar__right">
                <button type="button" class="admin-icon-btn" @click="toggleTheme()"
                        :aria-label="darkMode ? 'Switch to light theme' : 'Switch to dark theme'"
                        :title="darkMode ? 'Light mode' : 'Dark mode'">
                    <i class="fa-solid" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                </button>
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="admin-ghost-btn">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span class="d-none d-sm-inline">View Site</span>
                </a>
            </div>
        </header>

        <main class="admin-content">
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            icon: 'success',
                            title: 'Admin Action Saved',
                            text: @json(session('success')),
                            confirmButtonColor: '#003566'
                        });
                    });
                </script>
            @endif

            @if(session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: @json(session('error')),
                            confirmButtonColor: '#003566'
                        });
                    });
                </script>
            @endif

            @if($errors->any())
                <div class="alert alert-danger rounded-4 border-0 shadow-sm">
                    <p class="fw-bold mb-1"><i class="fa-solid fa-circle-exclamation me-1"></i> Please correct the following:</p>
                    <ul class="mb-0 ps-3 extra-small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Local Bootstrap 5 JS -->
    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    {{-- CKEditor 5 (~1.3 MB) is fetched on demand, only by pages that actually
         declare a [data-richtext] textarea, instead of on every admin screen. --}}
    <script>
        // Attach a rich-text editor to any [data-richtext] textarea, loading the
        // library on demand so screens without one pay nothing.
        document.addEventListener('DOMContentLoaded', function () {
            var fields = document.querySelectorAll('textarea[data-richtext]');
            if (!fields.length) return;

            var s = document.createElement('script');
            s.src = "{{ asset('vendor/ckeditor/ckeditor.js') }}";
            s.onload = function () {
                if (typeof ClassicEditor === 'undefined') return;
                fields.forEach(function (el) {
                    ClassicEditor.create(el).catch(function (err) {
                        console.error('Rich text editor failed to load:', err);
                    });
                });
            };
            s.onerror = function () {
                console.warn('CKEditor bundle could not be loaded; falling back to plain textarea.');
            };
            document.head.appendChild(s);
        });

        function adminShell() {
            return {
                collapsed: false,
                mobileOpen: false,
                darkMode: false,
                query: '',

                init() {
                    try {
                        this.darkMode = localStorage.getItem('theme') === 'dark';
                        this.collapsed = localStorage.getItem('kso.sidebar') === 'collapsed';
                    } catch (e) { /* storage unavailable */ }

                    // The pre-paint script set this; Alpine now owns the state.
                    document.documentElement.classList.remove('sidebar-is-collapsed');
                    document.documentElement.removeAttribute('data-bs-theme');

                    // Keep the active item in view on long menus.
                    this.$nextTick(() => {
                        const active = this.$refs.nav?.querySelector('.admin-nav-link.is-active');
                        if (active) active.scrollIntoView({ block: 'center' });
                    });
                },

                toggleCollapsed() {
                    this.collapsed = !this.collapsed;
                    try { localStorage.setItem('kso.sidebar', this.collapsed ? 'collapsed' : 'expanded'); } catch (e) {}
                },

                toggleTheme() {
                    this.darkMode = !this.darkMode;
                    try { localStorage.setItem('theme', this.darkMode ? 'dark' : 'light'); } catch (e) {}
                },

                matches(label) {
                    if (!this.query) return true;
                    return label.includes(this.query.trim().toLowerCase());
                },

                // Hide a whole group when the filter removes all of its items.
                sectionVisible(el) {
                    if (!this.query) return true;
                    const labels = Array.from(el.querySelectorAll('[data-label]'))
                        .map(a => a.dataset.label.toLowerCase());
                    return labels.some(l => l.includes(this.query.trim().toLowerCase()));
                }
            };
        }

        function confirmDelete(formId, message = 'Are you sure you want to delete this item?') {
            Swal.fire({
                title: 'Confirm Delete',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById(formId);
                    if (form) form.submit();
                }
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
