/* ─── KSO CHANDIGARH MAIN JAVASCRIPT ─── */

document.addEventListener('DOMContentLoaded', () => {
    initApp();
});

// Navigation Handler
function navigateTo(sectionId) {
    const sections = document.querySelectorAll('.page-section');
    sections.forEach(sec => sec.classList.add('d-none'));

    const targetSec = document.getElementById(`sec-${sectionId}`);
    if (targetSec) {
        targetSec.classList.remove('d-none');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Update active nav-links
    const navLinks = document.querySelectorAll('.navbar-modern .nav-link');
    navLinks.forEach(link => link.classList.remove('active'));

    const activeLink = document.getElementById(`nav-${sectionId.split('-')[0]}`);
    if (activeLink) activeLink.classList.add('active');

    // Update location hash silently
    if (history.pushState) {
        history.pushState(null, null, `#${sectionId}`);
    } else {
        location.hash = `#${sectionId}`;
    }

    // Trigger section-specific loads
    if (sectionId === 'about') loadFullCommittee();
    if (sectionId === 'events') loadAllEventsAndNews();
    if (sectionId === 'gallery') loadGallery();
    if (sectionId === 'donations') loadRecentDonors();
    if (sectionId === 'admin') checkAdminSession();
}

// App Initialization
function initApp() {
    loadSettings();
    loadHomeData();

    // Route based on URL hash
    const hash = window.location.hash.replace('#', '');
    if (hash) {
        navigateTo(hash);
    } else {
        navigateTo('home');
    }

    // Attach Event Listeners
    setupFormSubmitHandlers();
}

// Load Site Settings
async function loadSettings() {
    try {
        const res = await fetch('/api/settings');
        const data = await res.json();
        if (data.success && data.settings) {
            const s = data.settings;
            if (s.phone) {
                const el = document.getElementById('topPhone');
                if (el) { el.href = `tel:${s.phone}`; el.textContent = s.phone; }
            }
            if (s.helpline) {
                const el = document.getElementById('topHelpline');
                if (el) { el.href = `tel:${s.helpline}`; el.textContent = s.helpline; }
            }
            if (s.email) {
                const el = document.getElementById('topEmail');
                if (el) { el.href = `mailto:${s.email}`; el.textContent = s.email; }
            }
            if (s.announcement) {
                const el = document.getElementById('announcementBannerText');
                if (el) el.textContent = s.announcement;
            }
            if (s.address) {
                const el = document.getElementById('footerAddress');
                if (el) el.textContent = s.address;
            }
        }
    } catch (err) {
        console.error('Error loading settings:', err);
    }
}

// Load Home Page Data
async function loadHomeData() {
    try {
        // Stats
        const resMem = await fetch('/api/members');
        const dataMem = await resMem.json();
        if (dataMem.success) {
            document.getElementById('statMembersCount').textContent = `${dataMem.members.length}+`;
        }

        // Executive Body Preview (Top 3)
        const resCom = await fetch('/api/committee');
        const dataCom = await resCom.json();
        if (dataCom.success) {
            const homeCom = document.getElementById('homeCommitteeList');
            if (homeCom) {
                homeCom.innerHTML = dataCom.committee.slice(0, 4).map(c => `
                    <div class="col-lg-3 col-md-6 col-6">
                        <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-3 hover-lift bg-white">
                            <img src="${c.photo || '/images/default-avatar-m.png'}" class="rounded-circle mx-auto mb-3 border border-2 border-primary" style="width: 80px; height: 80px; object-fit: cover;" onerror="this.src='/images/default-avatar-m.png'">
                            <h6 class="fw-bold text-dark mb-1">${c.name}</h6>
                            <span class="badge bg-teal text-white rounded-pill px-2 py-1 extra-small mb-2" style="background:#0d9488;">${c.designation}</span>
                            <p class="text-muted extra-small mb-0"><i class="fa-solid fa-graduation-cap me-1"></i> ${c.institution}</p>
                        </div>
                    </div>
                `).join('');
            }
        }

        // Events Preview
        const resEv = await fetch('/api/events');
        const dataEv = await resEv.json();
        if (dataEv.success) {
            document.getElementById('statEventsCount').textContent = `${dataEv.events.length}+`;
            const homeEv = document.getElementById('homeEventsList');
            if (homeEv) {
                homeEv.innerHTML = dataEv.events.slice(0, 2).map(e => `
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden d-flex flex-row hover-lift bg-white">
                            <img src="${e.image || '/images/event-freshers.jpg'}" class="d-none d-sm-block" style="width: 140px; object-fit: cover;" onerror="this.src='/images/event-freshers.jpg'">
                            <div class="p-3 flex-grow-1">
                                <span class="badge bg-primary-lt text-primary extra-small fw-bold mb-1">${e.category}</span>
                                <h6 class="fw-bold text-dark mb-1">${e.title}</h6>
                                <div class="text-muted extra-small mb-2"><i class="fa-solid fa-calendar me-1"></i> ${e.date} • <i class="fa-solid fa-location-dot me-1"></i> ${e.venue}</div>
                                <button onclick="navigateTo('events')" class="btn btn-sm btn-outline-primary py-0 px-2 extra-small">View Event Details</button>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        }

        // News Preview
        const resNews = await fetch('/api/news');
        const dataNews = await resNews.json();
        if (dataNews.success && dataNews.news) {
            const homeNews = document.getElementById('homeNewsList');
            if (homeNews) {
                homeNews.innerHTML = dataNews.news.slice(0, 3).map(n => `
                    <div class="list-group-item list-group-item-action p-3 border-0 border-bottom">
                        <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                            <span class="badge bg-danger text-white extra-small">${n.category}</span>
                            <small class="text-muted extra-small"><i class="fa-solid fa-clock me-1"></i> ${n.date}</small>
                        </div>
                        <h6 class="mb-1 fw-bold text-dark fs-6">${n.title}</h6>
                        <p class="mb-1 text-secondary extra-small text-truncate" style="max-width: 320px;">${n.content}</p>
                    </div>
                `).join('');
            }
        }

    } catch (err) {
        console.error('Error loading home data:', err);
    }
}

// Load Full Committee (About Page)
async function loadFullCommittee() {
    try {
        const res = await fetch('/api/committee');
        const data = await res.json();
        if (data.success) {
            const fullCom = document.getElementById('fullCommitteeList');
            if (fullCom) {
                fullCom.innerHTML = data.committee.map(c => `
                    <div class="col-lg-4 col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 p-3 d-flex flex-row align-items-center bg-white hover-lift">
                            <img src="${c.photo || '/images/default-avatar-m.png'}" class="rounded-circle me-3 border border-2 border-primary" style="width: 85px; height: 80px; object-fit: cover;" onerror="this.src='/images/default-avatar-m.png'">
                            <div>
                                <h6 class="fw-bold text-dark mb-1">${c.name}</h6>
                                <span class="badge bg-teal text-white rounded-pill px-2 py-1 extra-small mb-1 d-inline-block" style="background:#0d9488;">${c.designation}</span>
                                <div class="text-muted extra-small mb-1"><i class="fa-solid fa-graduation-cap me-1"></i> ${c.institution}</div>
                                <div class="extra-small text-primary fw-semibold"><i class="fa-solid fa-phone me-1"></i> ${c.phone}</div>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        }
    } catch (err) {
        console.error(err);
    }
}

// Load All Events & News
async function loadAllEventsAndNews() {
    try {
        const resEv = await fetch('/api/events');
        const dataEv = await resEv.json();
        if (dataEv.success) {
            window.allEventsData = dataEv.events;
            renderEventsGrid(dataEv.events);
        }

        const resNews = await fetch('/api/news');
        const dataNews = await resNews.json();
        if (dataNews.success) {
            const grid = document.getElementById('allNewsGrid');
            if (grid) {
                grid.innerHTML = dataNews.news.map(n => `
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-danger text-white extra-small">${n.category}</span>
                                <small class="text-muted extra-small"><i class="fa-solid fa-calendar me-1"></i> ${n.date}</small>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">${n.title}</h5>
                            <p class="text-secondary extra-small mb-2">${n.content}</p>
                            <small class="text-primary fw-bold extra-small mt-auto"><i class="fa-solid fa-user-pen me-1"></i> ${n.author}</small>
                        </div>
                    </div>
                `).join('');
            }
        }
    } catch (err) {
        console.error(err);
    }
}

function filterEvents(cat) {
    const btns = document.querySelectorAll('#eventCategoryFilters button');
    btns.forEach(b => {
        if (b.textContent.includes(cat)) {
            b.classList.add('active', 'btn-primary');
            b.classList.remove('btn-outline-primary');
        } else {
            b.classList.remove('active', 'btn-primary');
            b.classList.add('btn-outline-primary');
        }
    });

    if (!window.allEventsData) return;
    if (cat === 'All') {
        renderEventsGrid(window.allEventsData);
    } else {
        const filtered = window.allEventsData.filter(e => e.category === cat);
        renderEventsGrid(filtered);
    }
}

function renderEventsGrid(events) {
    const grid = document.getElementById('allEventsGrid');
    if (!grid) return;
    if (events.length === 0) {
        grid.innerHTML = `<div class="col-12 text-center text-muted py-4">No events found in this category.</div>`;
        return;
    }
    grid.innerHTML = events.map(e => `
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white hover-lift">
                <img src="${e.image || '/images/event-freshers.jpg'}" class="card-img-top" style="height: 180px; object-fit: cover;" onerror="this.src='/images/event-freshers.jpg'">
                <div class="card-body p-3 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-primary-lt text-primary extra-small fw-bold">${e.category}</span>
                        <span class="badge ${e.status === 'Upcoming' ? 'bg-success' : 'bg-secondary'} extra-small">${e.status}</span>
                    </div>
                    <h5 class="card-title fw-bold text-dark mb-2">${e.title}</h5>
                    <p class="card-text text-muted extra-small mb-3">${e.description}</p>
                    <div class="mt-auto pt-2 border-top extra-small text-secondary">
                        <div class="mb-1"><i class="fa-solid fa-calendar-day text-primary me-1"></i> <strong>Date:</strong> ${e.date} (${e.time || '10:00 AM'})</div>
                        <div><i class="fa-solid fa-location-dot text-danger me-1"></i> <strong>Venue:</strong> ${e.venue}</div>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

// Load Gallery
async function loadGallery() {
    try {
        const res = await fetch('/api/gallery');
        const data = await res.json();
        if (data.success) {
            const grid = document.getElementById('galleryGrid');
            if (grid) {
                grid.innerHTML = data.gallery.map(g => `
                    <div class="col-md-4 col-6">
                        <div class="gallery-item shadow-sm border bg-white p-2 text-center">
                            <img src="${g.imageUrl}" class="img-fluid rounded gallery-img w-100" style="height: 200px; object-fit: cover;" alt="${g.title}" onerror="this.src='/images/gallery-1.jpg'">
                            <div class="mt-2">
                                <h6 class="fw-bold text-dark mb-0 extra-small">${g.title}</h6>
                                <span class="badge bg-light text-muted extra-small">${g.category}</span>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        }
    } catch (err) {
        console.error(err);
    }
}

// Load Donors
async function loadRecentDonors() {
    try {
        const res = await fetch('/api/donations');
        const data = await res.json();
        if (data.success) {
            const list = document.getElementById('recentDonorsList');
            if (list) {
                list.innerHTML = data.donations.slice(0, 5).map(d => `
                    <div class="d-flex justify-content-between align-items-center p-2 border-bottom extra-small">
                        <div>
                            <div class="fw-bold text-dark">${d.donorName}</div>
                            <div class="text-muted extra-small">${d.cause}</div>
                        </div>
                        <div class="fw-bold text-success fs-6">+ ₹${d.amount}</div>
                    </div>
                `).join('');
            }
        }
    } catch (err) {
        console.error(err);
    }
}

// Quick Donation Amount Handler
function setDonateAmt(amt, btn) {
    document.getElementById('customDonateAmt').value = amt;
    const btns = document.querySelectorAll('.quick-amt');
    btns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

// Setup Form Submission Handlers
function setupFormSubmitHandlers() {
    // 1. Member Registration Form
    const regForm = document.getElementById('memberRegisterForm');
    if (regForm) {
        regForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(regForm);

            try {
                const res = await fetch('/api/members/register', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if (data.success && data.member) {
                    regForm.reset();
                    // Show digital ID card modal instantly!
                    showIdCardModal(data.member);
                } else {
                    alert('Registration failed: ' + (data.message || 'Error occurred'));
                }
            } catch (err) {
                alert('Server error occurred during registration.');
            }
        });
    }

    // 2. ID Verification Form
    const verForm = document.getElementById('verifyForm');
    if (verForm) {
        verForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('verifyInput').value.trim();
            const resultBox = document.getElementById('verifyResult');
            resultBox.classList.remove('d-none');
            resultBox.innerHTML = `<div class="spinner-border text-primary" role="status"></div>`;

            try {
                const res = await fetch(`/api/members/verify/${encodeURIComponent(id)}`);
                const data = await res.json();
                if (data.success && data.member) {
                    const m = data.member;
                    const isApproved = m.status === 'Approved';
                    resultBox.innerHTML = `
                        <div class="alert ${isApproved ? 'alert-success border-success' : 'alert-warning border-warning'} text-start rounded-4 shadow-sm p-3">
                            <div class="d-flex align-items-center mb-3">
                                <img src="${m.photo}" class="rounded-circle me-3 border border-2" width="60" height="60" style="object-fit:cover;">
                                <div>
                                    <span class="badge ${isApproved ? 'bg-success' : 'bg-warning text-dark'} fw-bold mb-1">
                                        <i class="fa-solid ${isApproved ? 'fa-circle-check' : 'fa-clock'} me-1"></i> STATUS: ${m.status.toUpperCase()}
                                    </span>
                                    <h5 class="fw-bold mb-0 text-dark">${m.fullName}</h5>
                                    <small class="text-muted">${m.id}</small>
                                </div>
                            </div>
                            <table class="table table-sm table-borderless mb-0 extra-small">
                                <tr><td class="fw-bold text-muted">College:</td><td>${m.institution}</td></tr>
                                <tr><td class="fw-bold text-muted">Course:</td><td>${m.course} (${m.yearOfStudy})</td></tr>
                                <tr><td class="fw-bold text-muted">Membership:</td><td>${m.membershipType}</td></tr>
                                <tr><td class="fw-bold text-muted">Valid Until:</td><td class="fw-bold text-primary">${m.validUntil}</td></tr>
                            </table>
                        </div>
                    `;
                } else {
                    resultBox.innerHTML = `
                        <div class="alert alert-danger text-center rounded-4 shadow-sm p-3">
                            <i class="fa-solid fa-circle-xmark fs-2 text-danger mb-2"></i>
                            <h6 class="fw-bold mb-1">Verification Failed</h6>
                            <p class="extra-small mb-0">No active student record found matching ID: <code>${id}</code></p>
                        </div>
                    `;
                }
            } catch (err) {
                resultBox.innerHTML = `<div class="alert alert-danger">Error connecting to verification server.</div>`;
            }
        });
    }

    // 3. Member Portal Login Form
    const portalForm = document.getElementById('portalLoginForm');
    if (portalForm) {
        portalForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const identifier = document.getElementById('portalIdentifier').value.trim();
            try {
                const res = await fetch('/api/members/portal-login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ identifier })
                });
                const data = await res.json();
                if (data.success && data.member) {
                    showMemberPortalDashboard(data.member);
                } else {
                    alert(data.message || 'Login failed.');
                }
            } catch (err) {
                alert('Connection error.');
            }
        });
    }

    // 4. Donation Form
    const donForm = document.getElementById('donationForm');
    if (donForm) {
        donForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(donForm);
            const obj = Object.fromEntries(formData.entries());

            try {
                const res = await fetch('/api/donations', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(obj)
                });
                const data = await res.json();
                if (data.success) {
                    alert('Thank you! Your donation record has been submitted successfully.');
                    donForm.reset();
                    loadRecentDonors();
                }
            } catch (err) {
                alert('Error submitting donation.');
            }
        });
    }

    // 5. Contact Form
    const conForm = document.getElementById('contactForm');
    if (conForm) {
        conForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(conForm);
            const obj = Object.fromEntries(formData.entries());

            try {
                const res = await fetch('/api/messages', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(obj)
                });
                const data = await res.json();
                if (data.success) {
                    alert('Thank you! Your inquiry has been sent to KSO Chandigarh.');
                    conForm.reset();
                }
            } catch (err) {
                alert('Error sending message.');
            }
        });
    }

    // 6. Admin Login Form
    const admLoginForm = document.getElementById('adminLoginForm');
    if (admLoginForm) {
        admLoginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('adminUser').value;
            const password = document.getElementById('adminPass').value;

            try {
                const res = await fetch('/api/admin/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ username, password })
                });
                const data = await res.json();
                if (data.success) {
                    showAdminDashboard();
                } else {
                    alert(data.message || 'Invalid credentials');
                }
            } catch (err) {
                alert('Admin login error.');
            }
        });
    }
}

// DIGITAL MEMBERSHIP ID CARD HTML RENDERER
function renderDigitalIdCard(m) {
    return `
        <div class="id-card-wrapper shadow-lg text-start my-3" id="idCardPrintArea">
            <div class="id-card-header">
                <div class="d-flex align-items-center justify-content-center gap-2">
                    <img src="/images/kso-logo.jpg" onerror="this.src='/images/default-avatar-m.png'">
                    <div>
                        <h5 class="mb-0 text-white">KSO CHANDIGARH</h5>
                        <p class="text-warning fw-bold">Kuki Students' Organisation</p>
                    </div>
                </div>
            </div>
            
            <div class="id-card-body">
                <div class="id-card-photo-container">
                    <img src="${m.photo}" onerror="this.src='/images/default-avatar-m.png'">
                </div>

                <div class="text-center">
                    <div class="id-card-name">${m.fullName}</div>
                    <div class="id-card-num">${m.id}</div>
                </div>

                <table class="id-card-details w-100">
                    <tr><td class="label">College:</td><td class="fw-bold">${m.institution}</td></tr>
                    <tr><td class="label">Course:</td><td>${m.course} (${m.yearOfStudy})</td></tr>
                    <tr><td class="label">Blood Grp:</td><td class="fw-bold text-danger">${m.bloodGroup || 'O+'}</td></tr>
                    <tr><td class="label">Emergency:</td><td>${m.emergencyPhone || '+91 9876543210'}</td></tr>
                    <tr><td class="label">Status:</td><td><span class="badge ${m.status === 'Approved' ? 'bg-success' : 'bg-warning text-dark'} px-2 py-0 extra-small">${m.status.toUpperCase()}</span></td></tr>
                </table>
            </div>

            <div class="id-card-footer">
                <div>
                    <div class="fw-bold text-warning">VALID UNTIL: ${m.validUntil || '2027-06-30'}</div>
                    <div class="extra-small opacity-75">Recognized by KSO General HQ</div>
                </div>
                <img src="/api/qr/${m.id}" class="id-card-qr" alt="QR Code">
            </div>
        </div>
    `;
}

function showIdCardModal(member) {
    const modalBody = document.getElementById('idCardModalBody');
    if (modalBody) {
        modalBody.innerHTML = renderDigitalIdCard(member);
    }
    const bsModal = new bootstrap.Modal(document.getElementById('idCardModal'));
    bsModal.show();
}

function printCard(containerId) {
    const content = document.getElementById(containerId).innerHTML;
    const printWin = window.open('', '', 'width=600,height=700');
    printWin.document.write(`
        <html>
            <head>
                <title>KSO Membership ID Card</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
                <link href="/css/custom.css" rel="stylesheet">
                <style>
                    body { background: #fff; padding: 20px; text-align: center; }
                    .id-card-wrapper { margin: 0 auto; }
                </style>
            </head>
            <body>
                ${content}
                <script>
                    window.onload = function() { window.print(); window.close(); }
                </script>
            </body>
        </html>
    `);
    printWin.document.close();
}

// Show Member Portal Logged In View
function showMemberPortalDashboard(m) {
    document.getElementById('memberLoginBox').classList.add('d-none');
    const dash = document.getElementById('memberDashboardView');
    dash.classList.remove('d-none');

    document.getElementById('memberCardRender').innerHTML = renderDigitalIdCard(m);

    document.getElementById('memberDetailsTable').innerHTML = `
        <tr><th class="text-primary">Membership ID:</th><td class="fw-bold">${m.id}</td></tr>
        <tr><th class="text-primary">Full Name:</th><td>${m.fullName}</td></tr>
        <tr><th class="text-primary">Gender / DOB:</th><td>${m.gender} • ${m.dob}</td></tr>
        <tr><th class="text-primary">Phone / Email:</th><td>${m.phone} • ${m.email}</td></tr>
        <tr><th class="text-primary">College:</th><td>${m.institution}</td></tr>
        <tr><th class="text-primary">Course & Dept:</th><td>${m.course} (${m.department || 'N/A'}) - ${m.yearOfStudy}</td></tr>
        <tr><th class="text-primary">Permanent Address:</th><td>${m.permanentAddress}</td></tr>
        <tr><th class="text-primary">Current PG Address:</th><td>${m.currentAddress}</td></tr>
        <tr><th class="text-primary">Emergency Contact:</th><td>${m.emergencyContact} (${m.emergencyPhone})</td></tr>
        <tr><th class="text-primary">Application Status:</th><td><span class="badge ${m.status === 'Approved' ? 'bg-success' : 'bg-warning text-dark'}">${m.status}</span></td></tr>
    `;

    // Load Notices in Member Portal
    fetch('/api/news')
        .then(res => res.json())
        .then(d => {
            if (d.success) {
                document.getElementById('portalNoticesList').innerHTML = d.news.map(n => `
                    <div class="p-2 border-bottom extra-small">
                        <div class="fw-bold text-dark">${n.title}</div>
                        <div class="text-muted">${n.content}</div>
                        <small class="text-primary fw-semibold">${n.date}</small>
                    </div>
                `).join('');
            }
        });
}

function logoutMember() {
    document.getElementById('memberDashboardView').classList.add('d-none');
    document.getElementById('memberLoginBox').classList.remove('d-none');
}

// ─── ADMIN CMS LOGIC ───

async function checkAdminSession() {
    try {
        const res = await fetch('/api/admin/check');
        const data = await res.json();
        if (data.isAdmin) {
            showAdminDashboard();
        } else {
            showAdminLogin();
        }
    } catch (err) {
        showAdminLogin();
    }
}

function showAdminLogin() {
    document.getElementById('adminLoginContainer').classList.remove('d-none');
    document.getElementById('adminDashboardContainer').classList.add('d-none');
}

function showAdminDashboard() {
    document.getElementById('adminLoginContainer').classList.add('d-none');
    document.getElementById('adminDashboardContainer').classList.remove('d-none');
    switchAdminTab('members');
}

async function adminLogout() {
    await fetch('/api/admin/logout', { method: 'POST' });
    showAdminLogin();
}

function switchAdminTab(tabName) {
    const tabs = document.querySelectorAll('.adm-tab-pane');
    tabs.forEach(t => t.classList.add('d-none'));

    const targetTab = document.getElementById(`admTab-${tabName}`);
    if (targetTab) targetTab.classList.remove('d-none');

    const links = document.querySelectorAll('#adminTabs .nav-link');
    links.forEach(l => l.classList.remove('active'));

    // Trigger tab loaders
    if (tabName === 'members') loadAdminMembers();
    if (tabName === 'events') loadAdminEvents();
    if (tabName === 'news') loadAdminNews();
    if (tabName === 'committee') loadAdminCommittee();
    if (tabName === 'gallery') loadAdminGallery();
    if (tabName === 'donations') loadAdminDonations();
    if (tabName === 'messages') loadAdminMessages();
    if (tabName === 'settings') loadAdminSettings();
}

// Admin Tab 1: Members
async function loadAdminMembers() {
    const search = document.getElementById('admSearchMember').value;
    const status = document.getElementById('admFilterStatus').value;

    try {
        const res = await fetch(`/api/members?search=${encodeURIComponent(search)}&status=${encodeURIComponent(status)}`);
        const data = await res.json();
        if (data.success) {
            const members = data.members;
            
            // Update Metrics
            document.getElementById('admTotalMembers').textContent = members.length;
            const pending = members.filter(m => m.status === 'Pending').length;
            document.getElementById('admPendingMembers').textContent = pending;

            const tbody = document.getElementById('admMembersTable');
            tbody.innerHTML = members.map(m => `
                <tr>
                    <td><img src="${m.photo}" class="rounded-circle" width="36" height="36" style="object-fit:cover;" onerror="this.src='/images/default-avatar-m.png'"></td>
                    <td class="fw-bold text-primary">${m.id}</td>
                    <td class="fw-bold text-dark">${m.fullName}</td>
                    <td>${m.institution}</td>
                    <td>${m.course} (${m.yearOfStudy})</td>
                    <td>${m.phone}</td>
                    <td><span class="badge ${m.status === 'Approved' ? 'bg-success' : (m.status === 'Pending' ? 'bg-warning text-dark' : 'bg-danger')}">${m.status}</span></td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            ${m.status === 'Pending' ? `
                                <button class="btn btn-success" title="Approve" onclick="updateMemberStatus('${m.id}', 'Approved')"><i class="fa-solid fa-check"></i></button>
                                <button class="btn btn-warning text-dark" title="Reject" onclick="updateMemberStatus('${m.id}', 'Rejected')"><i class="fa-solid fa-xmark"></i></button>
                            ` : ''}
                            <button class="btn btn-outline-primary" title="View Card" onclick="viewMemberCard('${m.id}')"><i class="fa-solid fa-id-card"></i></button>
                            <button class="btn btn-outline-danger" title="Delete" onclick="deleteMember('${m.id}')"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }
    } catch (err) {
        console.error(err);
    }
}

async function updateMemberStatus(id, status) {
    try {
        const res = await fetch(`/api/members/${id}/status`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ status })
        });
        const data = await res.json();
        if (data.success) {
            loadAdminMembers();
        }
    } catch (err) {
        alert('Failed to update status');
    }
}

async function deleteMember(id) {
    if (!confirm(`Are you sure you want to delete member ${id}?`)) return;
    try {
        const res = await fetch(`/api/members/${id}`, { method: 'DELETE' });
        const data = await res.json();
        if (data.success) loadAdminMembers();
    } catch (err) {
        alert('Delete failed');
    }
}

async function viewMemberCard(id) {
    const res = await fetch(`/api/members/${id}`);
    const data = await res.json();
    if (data.success) {
        showIdCardModal(data.member);
    }
}

// Admin Tab 2: Events
async function loadAdminEvents() {
    const res = await fetch('/api/events');
    const data = await res.json();
    if (data.success) {
        document.getElementById('admTotalEvents').textContent = data.events.length;
        const tbody = document.getElementById('admEventsTable');
        tbody.innerHTML = data.events.map(e => `
            <tr>
                <td class="fw-bold">${e.title}</td>
                <td><span class="badge bg-primary-lt text-primary">${e.category}</span></td>
                <td>${e.date} (${e.time || ''})</td>
                <td>${e.venue}</td>
                <td><span class="badge ${e.status === 'Upcoming' ? 'bg-success' : 'bg-secondary'}">${e.status}</span></td>
                <td>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteEvent(${e.id})"><i class="fa-solid fa-trash"></i></button>
                </td>
            </tr>
        `).join('');
    }
}

function openAddEventModal() {
    const modalTitle = document.getElementById('cmsModalTitle');
    const modalBody = document.getElementById('cmsModalBody');
    modalTitle.textContent = 'Create New Event';
    modalBody.innerHTML = `
        <form id="addEventForm">
            <div class="row g-3">
                <div class="col-md-8"><label class="form-label fw-bold">Event Title</label><input type="text" class="form-control" name="title" required></div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Category</label>
                    <select class="form-select" name="category" required>
                        <option value="Cultural">Cultural</option>
                        <option value="Sports">Sports</option>
                        <option value="Academic">Academic</option>
                        <option value="Social Service">Social Service</option>
                    </select>
                </div>
                <div class="col-md-4"><label class="form-label fw-bold">Date</label><input type="date" class="form-control" name="date" required></div>
                <div class="col-md-4"><label class="form-label fw-bold">Time</label><input type="text" class="form-control" name="time" placeholder="10:00 AM - 04:00 PM"></div>
                <div class="col-md-4"><label class="form-label fw-bold">Status</label><select class="form-select" name="status"><option value="Upcoming">Upcoming</option><option value="Completed">Completed</option></select></div>
                <div class="col-12"><label class="form-label fw-bold">Venue</label><input type="text" class="form-control" name="venue" placeholder="Auditorium / Ground Name" required></div>
                <div class="col-12"><label class="form-label fw-bold">Description</label><textarea class="form-control" name="description" rows="3" required></textarea></div>
                <div class="col-12"><label class="form-label fw-bold">Event Image</label><input type="file" class="form-control" name="imageFile" accept="image/*"></div>
                <div class="col-12 text-end"><button type="submit" class="btn btn-primary fw-bold">Save Event</button></div>
            </div>
        </form>
    `;
    const bsModal = new bootstrap.Modal(document.getElementById('cmsModal'));
    bsModal.show();

    document.getElementById('addEventForm').onsubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        await fetch('/api/events', { method: 'POST', body: formData });
        bootstrap.Modal.getInstance(document.getElementById('cmsModal')).hide();
        loadAdminEvents();
    };
}

async function deleteEvent(id) {
    if (!confirm('Delete event?')) return;
    await fetch(`/api/events/${id}`, { method: 'DELETE' });
    loadAdminEvents();
}

// Admin Tab 3: News
async function loadAdminNews() {
    const res = await fetch('/api/news');
    const data = await res.json();
    if (data.success) {
        const tbody = document.getElementById('admNewsTable');
        tbody.innerHTML = data.news.map(n => `
            <tr>
                <td class="fw-bold">${n.title}</td>
                <td><span class="badge bg-danger">${n.category}</span></td>
                <td>${n.date}</td>
                <td>${n.author}</td>
                <td><button class="btn btn-sm btn-outline-danger" onclick="deleteNews(${n.id})"><i class="fa-solid fa-trash"></i></button></td>
            </tr>
        `).join('');
    }
}

function openAddNewsModal() {
    const modalTitle = document.getElementById('cmsModalTitle');
    const modalBody = document.getElementById('cmsModalBody');
    modalTitle.textContent = 'Post Announcement / News';
    modalBody.innerHTML = `
        <form id="addNewsForm">
            <div class="row g-3">
                <div class="col-md-8"><label class="form-label fw-bold">Headline Title</label><input type="text" class="form-control" name="title" required></div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Category</label>
                    <select class="form-select" name="category" required>
                        <option value="Notice">Notice</option>
                        <option value="Welfare">Welfare</option>
                        <option value="Academic">Academic</option>
                        <option value="Press Release">Press Release</option>
                    </select>
                </div>
                <div class="col-12"><label class="form-label fw-bold">Content</label><textarea class="form-control" name="content" rows="4" required></textarea></div>
                <div class="col-md-6"><label class="form-label fw-bold">Author</label><input type="text" class="form-control" name="author" value="Executive Desk"></div>
                <div class="col-12 text-end"><button type="submit" class="btn btn-primary fw-bold">Publish News</button></div>
            </div>
        </form>
    `;
    const bsModal = new bootstrap.Modal(document.getElementById('cmsModal'));
    bsModal.show();

    document.getElementById('addNewsForm').onsubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const obj = Object.fromEntries(formData.entries());
        await fetch('/api/news', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(obj)
        });
        bootstrap.Modal.getInstance(document.getElementById('cmsModal')).hide();
        loadAdminNews();
    };
}

async function deleteNews(id) {
    if (!confirm('Delete news item?')) return;
    await fetch(`/api/news/${id}`, { method: 'DELETE' });
    loadAdminNews();
}

// Admin Tab 4: Committee
async function loadAdminCommittee() {
    const res = await fetch('/api/committee');
    const data = await res.json();
    if (data.success) {
        const tbody = document.getElementById('admCommitteeTable');
        tbody.innerHTML = data.committee.map(c => `
            <tr>
                <td class="fw-bold">${c.name}</td>
                <td><span class="badge bg-teal text-white" style="background:#0d9488;">${c.designation}</span></td>
                <td>${c.institution}</td>
                <td>${c.phone}</td>
                <td>${c.tenure}</td>
                <td><button class="btn btn-sm btn-outline-danger" onclick="deleteCommittee(${c.id})"><i class="fa-solid fa-trash"></i></button></td>
            </tr>
        `).join('');
    }
}

function openAddCommitteeModal() {
    const modalTitle = document.getElementById('cmsModalTitle');
    const modalBody = document.getElementById('cmsModalBody');
    modalTitle.textContent = 'Add Executive Committee Leader';
    modalBody.innerHTML = `
        <form id="addCommitteeForm">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label fw-bold">Full Name</label><input type="text" class="form-control" name="name" required></div>
                <div class="col-md-6"><label class="form-label fw-bold">Designation</label><input type="text" class="form-control" name="designation" placeholder="e.g. President / Vice President" required></div>
                <div class="col-md-6"><label class="form-label fw-bold">Institution</label><input type="text" class="form-control" name="institution" required></div>
                <div class="col-md-6"><label class="form-label fw-bold">Phone</label><input type="text" class="form-control" name="phone" required></div>
                <div class="col-md-6"><label class="form-label fw-bold">Tenure</label><input type="text" class="form-control" name="tenure" value="2025 - 2026"></div>
                <div class="col-md-6"><label class="form-label fw-bold">Photo</label><input type="file" class="form-control" name="photoFile" accept="image/*"></div>
                <div class="col-12 text-end"><button type="submit" class="btn btn-primary fw-bold">Add Executive Member</button></div>
            </div>
        </form>
    `;
    const bsModal = new bootstrap.Modal(document.getElementById('cmsModal'));
    bsModal.show();

    document.getElementById('addCommitteeForm').onsubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        await fetch('/api/committee', { method: 'POST', body: formData });
        bootstrap.Modal.getInstance(document.getElementById('cmsModal')).hide();
        loadAdminCommittee();
    };
}

async function deleteCommittee(id) {
    if (!confirm('Remove from committee?')) return;
    await fetch(`/api/committee/${id}`, { method: 'DELETE' });
    loadAdminCommittee();
}

// Admin Tab 5: Gallery
async function loadAdminGallery() {
    const res = await fetch('/api/gallery');
    const data = await res.json();
    if (data.success) {
        const grid = document.getElementById('admGalleryGrid');
        grid.innerHTML = data.gallery.map(g => `
            <div class="col-md-3 col-6">
                <div class="card border p-2 position-relative text-center">
                    <img src="${g.imageUrl}" class="img-fluid rounded mb-2" style="height:120px; object-fit:cover;">
                    <small class="fw-bold text-truncate d-block">${g.title}</small>
                    <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" onclick="deleteGallery(${g.id})"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
        `).join('');
    }
}

function openAddGalleryModal() {
    const modalTitle = document.getElementById('cmsModalTitle');
    const modalBody = document.getElementById('cmsModalBody');
    modalTitle.textContent = 'Upload Gallery Photo';
    modalBody.innerHTML = `
        <form id="addGalleryForm">
            <div class="row g-3">
                <div class="col-md-8"><label class="form-label fw-bold">Image Title</label><input type="text" class="form-control" name="title" required></div>
                <div class="col-md-4"><label class="form-label fw-bold">Category</label><input type="text" class="form-control" name="category" placeholder="Cultural / Sports" required></div>
                <div class="col-12"><label class="form-label fw-bold">Image File</label><input type="file" class="form-control" name="imageFile" accept="image/*" required></div>
                <div class="col-12 text-end"><button type="submit" class="btn btn-primary fw-bold">Upload</button></div>
            </div>
        </form>
    `;
    const bsModal = new bootstrap.Modal(document.getElementById('cmsModal'));
    bsModal.show();

    document.getElementById('addGalleryForm').onsubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        await fetch('/api/gallery', { method: 'POST', body: formData });
        bootstrap.Modal.getInstance(document.getElementById('cmsModal')).hide();
        loadAdminGallery();
    };
}

async function deleteGallery(id) {
    if (!confirm('Delete photo?')) return;
    await fetch(`/api/gallery/${id}`, { method: 'DELETE' });
    loadAdminGallery();
}

// Admin Tab 6: Donations
async function loadAdminDonations() {
    const res = await fetch('/api/donations');
    const data = await res.json();
    if (data.success) {
        const total = data.donations.reduce((sum, d) => sum + Number(d.amount || 0), 0);
        document.getElementById('admTotalDonations').textContent = `₹${total}`;
        const tbody = document.getElementById('admDonationsTable');
        tbody.innerHTML = data.donations.map(d => `
            <tr>
                <td class="fw-bold">${d.donorName}</td>
                <td class="fw-bold text-success">₹${d.amount}</td>
                <td>${d.cause}</td>
                <td>${d.phone || ''} ${d.email || ''}</td>
                <td><code>${d.paymentRef}</code></td>
                <td>${d.date}</td>
            </tr>
        `).join('');
    }
}

// Admin Tab 7: Messages
async function loadAdminMessages() {
    const res = await fetch('/api/messages');
    const data = await res.json();
    if (data.success) {
        const tbody = document.getElementById('admMessagesTable');
        tbody.innerHTML = data.messages.map(m => `
            <tr>
                <td class="fw-bold">${m.name}<br><small class="text-muted">${m.phone}</small></td>
                <td class="fw-bold text-primary">${m.subject}</td>
                <td>${m.message}</td>
                <td>${m.date}</td>
                <td><span class="badge ${m.status === 'Unread' ? 'bg-danger' : 'bg-success'}">${m.status}</span></td>
                <td>
                    ${m.status === 'Unread' ? `<button class="btn btn-sm btn-outline-success" onclick="markMessageStatus(${m.id}, 'Resolved')">Mark Resolved</button>` : ''}
                </td>
            </tr>
        `).join('');
    }
}

async function markMessageStatus(id, status) {
    await fetch(`/api/messages/${id}/status`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ status })
    });
    loadAdminMessages();
}

// Admin Tab 8: Settings
async function loadAdminSettings() {
    const res = await fetch('/api/settings');
    const data = await res.json();
    if (data.success && data.settings) {
        const s = data.settings;
        document.getElementById('setSiteName').value = s.siteName || '';
        document.getElementById('setTagline').value = s.tagline || '';
        document.getElementById('setEmail').value = s.email || '';
        document.getElementById('setPhone').value = s.phone || '';
        document.getElementById('setHelpline').value = s.helpline || '';
        document.getElementById('setAnnouncement').value = s.announcement || '';
        document.getElementById('setAddress').value = s.address || '';
    }

    document.getElementById('admSettingsForm').onsubmit = async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const obj = Object.fromEntries(formData.entries());
        await fetch('/api/settings', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(obj)
        });
        alert('Settings updated successfully!');
        loadSettings();
    };
}
