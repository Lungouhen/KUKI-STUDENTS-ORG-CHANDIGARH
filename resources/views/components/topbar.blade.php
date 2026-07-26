<div class="topbar d-none d-md-block">
    <div class="container-fluid px-lg-5">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <span class="me-3"><i class="fa-solid fa-phone topbar-icon"></i> <a href="tel:{{ \App\Models\Setting::get('phone', '+91 98765 43210') }}">{{ \App\Models\Setting::get('phone', '+91 98765 43210') }}</a></span>
                <span class="topbar-sep">|</span>
                <span class="me-3"><i class="fa-solid fa-headset topbar-icon"></i> Helpline: <a href="tel:{{ \App\Models\Setting::get('helpline', '+91 98765 43211') }}" class="fw-bold text-warning">{{ \App\Models\Setting::get('helpline', '+91 98765 43211') }}</a></span>
                <span class="topbar-sep">|</span>
                <span><i class="fa-solid fa-envelope topbar-icon"></i> <a href="mailto:{{ \App\Models\Setting::get('email', 'ksochandigarh@gmail.com') }}">{{ \App\Models\Setting::get('email', 'ksochandigarh@gmail.com') }}</a></span>
            </div>
            <div class="d-flex align-items-center">
                <button @click="toggleTheme()" class="btn btn-sm p-0 me-3 text-white border-0 shadow-none" title="Toggle Theme">
                    <i class="fa-solid" :class="darkMode ? 'fa-sun text-warning' : 'fa-moon'"></i>
                </button>
                <span class="me-2 extra-small">Follow Us:</span>
                <div class="topbar-social">
                    <a href="{{ \App\Models\Setting::get('facebook', 'https://facebook.com') }}" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="{{ \App\Models\Setting::get('instagram', 'https://instagram.com') }}" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('whatsapp', '919876543210')) }}" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
