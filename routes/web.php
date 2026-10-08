<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\MemberDocumentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SeoController as PublicSeoController;

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
use App\Http\Controllers\Admin\AuditLogController as AdminAuditLogController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MembershipFormController as AdminMembershipFormController;
use App\Http\Controllers\Admin\AccommodationController as AdminAccommodationController;
use App\Http\Controllers\Admin\StudentResourceController as AdminStudentResourceController;
use App\Http\Controllers\Admin\MemberDocumentController as AdminMemberDocumentController;
use App\Http\Controllers\Admin\MemberDocumentTemplateController as AdminMemberDocumentTemplateController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Admin\CacheManagerController as AdminCacheManagerController;
use App\Http\Controllers\Admin\SeoController as AdminSeoController;

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
Route::get('/robots.txt', [PublicSeoController::class, 'robots'])->name('robots');
Route::get('/sitemap.xml', [PublicSeoController::class, 'sitemap'])->name('sitemap');
Route::get('/ads/{id}/click', [HomeController::class, 'clickAd'])->name('ads.click');

// Membership/Members Routes
Route::prefix('members')->group(function () {
    Route::get('/register', [MembershipController::class, 'registerForm'])->name('membership.register');
    Route::post('/register', [MembershipController::class, 'store'])->name('membership.store');
    Route::get('/verify', [MembershipController::class, 'verifyForm'])->name('membership.verifyForm');
    Route::post('/verify', [MembershipController::class, 'verify'])->name('membership.verify');
    Route::get('/verify/{id}', [MembershipController::class, 'verifyDirect'])->name('membership.verifyDirect');
    Route::get('/portal/documents/{id}', [MemberDocumentController::class, 'show'])->name('membership.documents.show');
    Route::post('/portal/documents', [MemberDocumentController::class, 'storeRequest'])->middleware('throttle:5,1')->name('membership.documents.request');
    Route::get('/portal', [MembershipController::class, 'portalForm'])->name('membership.portal');
    Route::post('/portal/login', [MembershipController::class, 'portalLogin'])->middleware('throttle:10,1')->name('membership.portalLogin');
    Route::get('/portal/dashboard', [MembershipController::class, 'portalDashboard'])->name('membership.portalDashboard');
    Route::post('/portal/post', [MembershipController::class, 'storeStudentPost'])->name('membership.storeStudentPost');
    Route::post('/portal/vote', [MembershipController::class, 'castVote'])->name('membership.castVote');
    Route::get('/portal/logout', [MembershipController::class, 'portalLogout'])->name('membership.portalLogout');
    Route::get('/portal/resources/{id}/download', [MembershipController::class, 'downloadResource'])->name('membership.downloadResource');
    Route::get('/id-card/{id}', [MembershipController::class, 'idCard'])->name('membership.idCard');
});
Route::get('/documents/verify/{certificateNumber}', [MemberDocumentController::class, 'verify'])
    ->middleware('throttle:10,1')
    ->where('certificateNumber', '[A-Za-z0-9-]{1,80}')
    ->name('documents.verify');

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
Route::get('/admin', function () {
    return redirect()->route('admin.login');
});
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1')->name('admin.login.post');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Routes
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Financial Management Module
    Route::get('/financial', [AdminFinancialController::class, 'index'])->name('financial.index');
    Route::get('/financial/print', [AdminFinancialController::class, 'print'])->name('financial.print');
    Route::get('/financial/export', [AdminFinancialController::class, 'export'])->name('financial.export');
    Route::post('/financial/transaction', [AdminFinancialController::class, 'storeTransaction'])->name('financial.storeTransaction');
    Route::post('/financial/account', [AdminFinancialController::class, 'createAccount'])->name('financial.createAccount');

    // Page Builder CMS
    Route::get('/pages', [AdminPageController::class, 'index'])->name('pages.index');
    Route::get('/pages/create', [AdminPageController::class, 'create'])->name('pages.create');
    Route::post('/pages', [AdminPageController::class, 'store'])->name('pages.store');
    Route::post('/pages/bulk', [AdminPageController::class, 'bulk'])->name('pages.bulk');
    Route::get('/pages/{id}/preview', [AdminPageController::class, 'preview'])->name('pages.preview');
    Route::get('/pages/{id}/revisions/{revisionId}/preview', [AdminPageController::class, 'previewRevision'])->name('pages.revisions.preview');
    Route::get('/pages/{id}/revisions/{revisionId}/compare', [AdminPageController::class, 'compareRevision'])->name('pages.revisions.compare');
    Route::post('/pages/{id}/revisions/{revisionId}/restore', [AdminPageController::class, 'restoreRevision'])->name('pages.revisions.restore');
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

    // Content Management
    Route::get('/content', [ContentController::class, 'index'])->name('content.index');
    Route::get('/content/sliders', [ContentController::class, 'sliders'])->name('content.sliders');
    Route::get('/content/certificates', [ContentController::class, 'certificates'])->name('content.certificates');
    Route::get('/content/achievements', [ContentController::class, 'achievements'])->name('content.achievements');
    Route::get('/content/policies', [ContentController::class, 'policies'])->name('content.policies');
    Route::get('/content/notices', [ContentController::class, 'notices'])->name('content.notices');
    Route::get('/content/campaigns', [ContentController::class, 'campaigns'])->name('content.campaigns');
    Route::get('/content/careers', [ContentController::class, 'careers'])->name('content.careers');
    Route::post('/content', [ContentController::class, 'store'])->name('content.store');
    Route::post('/content/bulk', [ContentController::class, 'bulk'])->name('content.bulk');
    Route::delete('/content/{id}', [ContentController::class, 'destroy'])->name('content.destroy');

    // Shared image library
    Route::get('/media', [AdminMediaController::class, 'index'])->name('media.index');
    Route::post('/media', [AdminMediaController::class, 'store'])->name('media.store');
    Route::put('/media/{id}', [AdminMediaController::class, 'update'])->name('media.update');
    Route::delete('/media/{id}', [AdminMediaController::class, 'destroy'])->name('media.destroy');

    // Audit Trail Logs
    Route::get('/audit', [AdminAuditLogController::class, 'index'])->name('audit.index');

    // Members Management
    Route::get('/member-documents', [AdminMemberDocumentController::class, 'index'])->name('memberDocuments.index');
    Route::post('/member-documents/direct/preview', [AdminMemberDocumentController::class, 'previewDirect'])->name('memberDocuments.previewDirect');
    Route::post('/member-documents/direct/issue', [AdminMemberDocumentController::class, 'generateDirect'])->name('memberDocuments.generateDirect');
    Route::post('/member-documents/bulk/preview', [AdminMemberDocumentController::class, 'previewBulk'])->name('memberDocuments.previewBulk');
    Route::post('/member-documents/bulk/issue', [AdminMemberDocumentController::class, 'generateBulk'])->name('memberDocuments.generateBulk');
    Route::get('/member-documents/batches/{id}', [AdminMemberDocumentController::class, 'showBatch'])->name('memberDocuments.batches.show');
    Route::post('/member-documents/batches/{id}/retry', [AdminMemberDocumentController::class, 'retryBatch'])->name('memberDocuments.batches.retry');
    Route::get('/member-documents/batches/{id}/print', [AdminMemberDocumentController::class, 'printBatch'])->name('memberDocuments.batches.print');
    Route::get('/member-document-templates', [AdminMemberDocumentTemplateController::class, 'index'])->name('memberDocumentTemplates.index');
    Route::post('/member-document-templates/preview', [AdminMemberDocumentTemplateController::class, 'preview'])->name('memberDocumentTemplates.preview');
    Route::post('/member-document-templates', [AdminMemberDocumentTemplateController::class, 'store'])->name('memberDocumentTemplates.store');
    Route::post('/member-documents/{id}/preview', [AdminMemberDocumentController::class, 'previewPending'])->name('memberDocuments.previewPending');
    Route::get('/member-documents/{id}/preview-issued', [AdminMemberDocumentController::class, 'previewIssued'])->name('memberDocuments.previewIssued');
    Route::post('/member-documents/{id}/issue', [AdminMemberDocumentController::class, 'issue'])->name('memberDocuments.issue');
    Route::post('/member-documents/{id}/reject', [AdminMemberDocumentController::class, 'reject'])->name('memberDocuments.reject');
    Route::post('/member-documents/{id}/revoke', [AdminMemberDocumentController::class, 'revoke'])->name('memberDocuments.revoke');
    Route::get('/membership-forms', [AdminMembershipFormController::class, 'index'])->name('membershipForms.index');
    Route::get('/membership-forms/print', [AdminMembershipFormController::class, 'print'])->name('membershipForms.print');
    Route::get('/membership-forms/download', [AdminMembershipFormController::class, 'download'])->name('membershipForms.download');
    Route::get('/members/fees', [AdminMemberController::class, 'fees'])->name('members.fees');
    Route::post('/members/{id}/fees', [AdminMemberController::class, 'recordFeePayment'])->name('members.recordFee');
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
    Route::post('/events/bulk', [AdminEventController::class, 'bulk'])->name('events.bulk');
    Route::get('/events/{id}/edit', [AdminEventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{id}', [AdminEventController::class, 'update'])->name('events.update');
    Route::delete('/events/{id}', [AdminEventController::class, 'destroy'])->name('events.destroy');

    // News
    Route::get('/news', [AdminNewsController::class, 'index'])->name('news.index');
    Route::post('/news', [AdminNewsController::class, 'store'])->name('news.store');
    Route::post('/news/bulk', [AdminNewsController::class, 'bulk'])->name('news.bulk');
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

    // Accommodations (Hostel & PG directory)
    Route::resource('accommodations', AdminAccommodationController::class)->except(['show']);

    // Student resources library
    Route::get('/resources', [AdminStudentResourceController::class, 'index'])->name('resources.index');
    Route::post('/resources', [AdminStudentResourceController::class, 'store'])->name('resources.store');
    Route::post('/resources/{id}/toggle', [AdminStudentResourceController::class, 'toggle'])->name('resources.toggle');
    Route::delete('/resources/{id}', [AdminStudentResourceController::class, 'destroy'])->name('resources.destroy');

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
    Route::post('/settings/smtp/test', [AdminSettingController::class, 'testSmtp'])->middleware('throttle:3,1')->name('settings.smtp.test');
    Route::get('/settings/smtp', [AdminSettingController::class, 'smtp'])->name('settings.smtp');
    Route::get('/settings/gateways', [AdminSettingController::class, 'gateways'])->name('settings.gateways');
    Route::get('/cache', [AdminCacheManagerController::class, 'index'])->name('cache.index');
    Route::post('/cache/{type}/clear', [AdminCacheManagerController::class, 'clear'])
        ->whereIn('type', ['application', 'config', 'routes', 'views', 'all'])
        ->name('cache.clear');
    Route::get('/seo', [AdminSeoController::class, 'index'])->name('seo.index');
    Route::post('/seo', [AdminSeoController::class, 'update'])->name('seo.update');
});
