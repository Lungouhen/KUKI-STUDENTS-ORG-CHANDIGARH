<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\CommitteeController as AdminCommitteeController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\DonationController as AdminDonationController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\FinancialController as AdminFinancialController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\MedicalReliefController as AdminMedicalReliefController;
use App\Http\Controllers\Admin\AuditLogController as AdminAuditLogController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes - KSO CHANDIGARH NGO & CMS PLATFORM
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');
Route::get('/faqs', [PageController::class, 'faqs'])->name('page.faqs');

// Membership Routes
Route::get('/membership/register', [MembershipController::class, 'registerForm'])->name('membership.register');
Route::post('/membership/register', [MembershipController::class, 'store'])->name('membership.store');
Route::get('/membership/verify', [MembershipController::class, 'verifyForm'])->name('membership.verifyForm');
Route::post('/membership/verify', [MembershipController::class, 'verify'])->name('membership.verify');
Route::get('/membership/verify/{id}', [MembershipController::class, 'verifyDirect'])->name('membership.verifyDirect');
Route::get('/membership/portal', [MembershipController::class, 'portalForm'])->name('membership.portal');
Route::post('/membership/portal/login', [MembershipController::class, 'portalLogin'])->name('membership.portalLogin');
Route::get('/membership/portal/dashboard', [MembershipController::class, 'portalDashboard'])->name('membership.portalDashboard');
Route::post('/membership/portal/medical-claim', [MembershipController::class, 'submitMedicalClaim'])->name('membership.submitMedicalClaim');
Route::get('/membership/portal/logout', [MembershipController::class, 'portalLogout'])->name('membership.portalLogout');
Route::get('/membership/id-card/{id}', [MembershipController::class, 'idCard'])->name('membership.idCard');

// Events & News
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
Route::post('/events/{id}/register', [EventController::class, 'registerAttendee'])->name('events.registerAttendee');
Route::get('/events/ticket/{ticketCode}', [EventController::class, 'ticketPass'])->name('events.ticketPass');

// Gallery
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

// Donations
Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');
Route::post('/donations/razorpay-order', [DonationController::class, 'createRazorpayOrder'])->name('donations.razorpayOrder');

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Admin Auth Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Routes
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Financial Management Module
    Route::get('/financial', [AdminFinancialController::class, 'index'])->name('financial.index');
    Route::post('/financial/transaction', [AdminFinancialController::class, 'storeTransaction'])->name('financial.storeTransaction');
    Route::post('/financial/account', [AdminFinancialController::class, 'createAccount'])->name('financial.createAccount');

    // Page Builder CMS
    Route::get('/pages', [AdminPageController::class, 'index'])->name('pages.index');
    Route::get('/pages/create', [AdminPageController::class, 'create'])->name('pages.create');
    Route::post('/pages', [AdminPageController::class, 'store'])->name('pages.store');
    Route::get('/pages/{id}/edit', [AdminPageController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{id}', [AdminPageController::class, 'update'])->name('pages.update');
    Route::delete('/pages/{id}', [AdminPageController::class, 'destroy'])->name('pages.destroy');

    // FAQs Manager
    Route::get('/faqs', [AdminFaqController::class, 'index'])->name('faqs.index');
    Route::post('/faqs', [AdminFaqController::class, 'store'])->name('faqs.store');
    Route::get('/faqs/{id}/edit', [AdminFaqController::class, 'edit'])->name('faqs.edit');
    Route::put('/faqs/{id}', [AdminFaqController::class, 'update'])->name('faqs.update');
    Route::delete('/faqs/{id}', [AdminFaqController::class, 'destroy'])->name('faqs.destroy');

    // Testimonials Manager
    Route::get('/testimonials', [AdminTestimonialController::class, 'index'])->name('testimonials.index');
    Route::post('/testimonials', [AdminTestimonialController::class, 'store'])->name('testimonials.store');
    Route::get('/testimonials/{id}/edit', [AdminTestimonialController::class, 'edit'])->name('testimonials.edit');
    Route::put('/testimonials/{id}', [AdminTestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('/testimonials/{id}', [AdminTestimonialController::class, 'destroy'])->name('testimonials.destroy');

    // Medical Emergency Relief Desk
    Route::get('/medical', [AdminMedicalReliefController::class, 'index'])->name('medical.index');
    Route::post('/medical/{id}/status', [AdminMedicalReliefController::class, 'updateStatus'])->name('medical.updateStatus');

    // Content Management
    Route::get('/content', [ContentController::class, 'index'])->name('content.index');
    Route::post('/content', [ContentController::class, 'store'])->name('content.store');
    Route::delete('/content/{id}', [ContentController::class, 'destroy'])->name('content.destroy');

    // Audit Trail Logs
    Route::get('/audit', [AdminAuditLogController::class, 'index'])->name('audit.index');

    // Members Management
    Route::get('/members/fees', [AdminMemberController::class, 'fees'])->name('members.fees');
    Route::get('/members', [AdminMemberController::class, 'index'])->name('members.index');
    Route::get('/members/create', [AdminMemberController::class, 'create'])->name('members.create');
    Route::post('/members', [AdminMemberController::class, 'store'])->name('members.store');
    Route::get('/members/{id}', [AdminMemberController::class, 'show'])->name('members.show');
    Route::get('/members/{id}/edit', [AdminMemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{id}', [AdminMemberController::class, 'update'])->name('members.update');
    Route::post('/members/{id}/status', [AdminMemberController::class, 'updateStatus'])->name('members.updateStatus');
    Route::delete('/members/{id}', [AdminMemberController::class, 'destroy'])->name('members.destroy');
    Route::get('/members/export/csv', [AdminMemberController::class, 'exportCsv'])->name('members.exportCsv');

    // Events
    Route::get('/events', [AdminEventController::class, 'index'])->name('events.index');
    Route::post('/events', [AdminEventController::class, 'store'])->name('events.store');
    Route::get('/events/{id}/edit', [AdminEventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{id}', [AdminEventController::class, 'update'])->name('events.update');
    Route::delete('/events/{id}', [AdminEventController::class, 'destroy'])->name('events.destroy');

    // News
    Route::get('/news', [AdminNewsController::class, 'index'])->name('news.index');
    Route::post('/news', [AdminNewsController::class, 'store'])->name('news.store');
    Route::get('/news/{id}/edit', [AdminNewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{id}', [AdminNewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{id}', [AdminNewsController::class, 'destroy'])->name('news.destroy');

    // Committee
    Route::get('/committee', [AdminCommitteeController::class, 'index'])->name('committee.index');
    Route::post('/committee', [AdminCommitteeController::class, 'store'])->name('committee.store');
    Route::get('/committee/{id}/edit', [AdminCommitteeController::class, 'edit'])->name('committee.edit');
    Route::put('/committee/{id}', [AdminCommitteeController::class, 'update'])->name('committee.update');
    Route::delete('/committee/{id}', [AdminCommitteeController::class, 'destroy'])->name('committee.destroy');

    // Gallery
    Route::get('/gallery', [AdminGalleryController::class, 'index'])->name('gallery.index');
    Route::post('/gallery', [AdminGalleryController::class, 'store'])->name('gallery.store');
    Route::delete('/gallery/{id}', [AdminGalleryController::class, 'destroy'])->name('gallery.destroy');

    // Donations
    Route::get('/donations', [AdminDonationController::class, 'index'])->name('donations.index');
    Route::get('/donations/{id}/receipt', [AdminDonationController::class, 'receipt'])->name('donations.receipt');

    // Partners
    Route::get('/partners/export/csv', [PartnerController::class, 'exportCsv'])->name('partners.exportCsv');
    Route::resource('partners', PartnerController::class);

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    // Projects & Beneficiaries
    Route::resource('projects', ProjectController::class);
    Route::get('/beneficiaries', [AdminDashboardController::class, 'beneficiaries'])->name('beneficiaries.index');

    // Terms & Elections
    Route::get('/terms', [AdminDashboardController::class, 'terms'])->name('terms.index');
    Route::post('/terms', [AdminDashboardController::class, 'storeTerm'])->name('terms.store');
    Route::resource('elections', ElectionController::class);
    Route::post('/elections/{id}/candidates', [ElectionController::class, 'addCandidate'])->name('elections.addCandidate');
    Route::post('/candidates/{id}/votes', [ElectionController::class, 'updateVotes'])->name('candidates.updateVotes');

    // Messages
    Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
    Route::post('/messages/{id}/status', [AdminMessageController::class, 'updateStatus'])->name('messages.updateStatus');

    // Settings
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::get('/settings/integrations', [AdminSettingController::class, 'integrations'])->name('settings.integrations');
    Route::get('/settings/smtp', [AdminSettingController::class, 'smtp'])->name('settings.smtp');
    Route::get('/settings/gateways', [AdminSettingController::class, 'gateways'])->name('settings.gateways');
});
