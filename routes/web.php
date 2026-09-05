<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AdminAnnouncementController;
use App\Http\Controllers\Admin\AdminCandidateController;
use App\Http\Controllers\Admin\AdminCommissionerController;
use App\Http\Controllers\Admin\AdminComplaintController;
use App\Http\Controllers\Admin\AdminConstituencyController;
use App\Http\Controllers\Admin\AdminContactController;
use App\Http\Controllers\Admin\AdminBallotController;
use App\Http\Controllers\Admin\AdminCountryController;
use App\Http\Controllers\Admin\AdminDiasporaMissionController;
use App\Http\Controllers\Admin\AdminDownloadController;
use App\Http\Controllers\Admin\AdminEducationController;
use App\Http\Controllers\Admin\AdminFaqController;
use App\Http\Controllers\Admin\AdminGalleryController;
use App\Http\Controllers\Admin\AdminNewsController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminObserverController;
use App\Http\Controllers\Admin\AdminPartyController;
use App\Http\Controllers\Admin\AdminPetitionController;
use App\Http\Controllers\Admin\AdminPollingStationController;
use App\Http\Controllers\Admin\AdminPollingStaffController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminResultController;
use App\Http\Controllers\Admin\AdminSecurityLogController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminSpeechController;
use App\Http\Controllers\Admin\AdminStaffController;
use App\Http\Controllers\Admin\AdminSubscriberController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminVideoController;
use App\Http\Controllers\Admin\AdminVoterController;
use App\Http\Controllers\Admin\AdminVoterTransferController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Api\GeographicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ConstituencyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\ElectionController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\GisController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ObserverController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\VoterAuthController;
use App\Http\Controllers\VoterController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// About
Route::prefix('about')->name('about.')->group(function () {
    Route::get('/', [AboutController::class, 'index'])->name('index');
    Route::get('/mandate', [AboutController::class, 'mandate'])->name('mandate');
    Route::get('/leadership', [AboutController::class, 'leadership'])->name('leadership');
    Route::get('/commissioners', [AboutController::class, 'commissioners'])->name('commissioners');
    Route::get('/state-committees', [AboutController::class, 'stateCommittees'])->name('state-committees');
    Route::get('/departments', [AboutController::class, 'departments'])->name('departments');
    Route::get('/history', [AboutController::class, 'history'])->name('history');
    Route::get('/legal-framework', [AboutController::class, 'legalFramework'])->name('legal-framework');
    Route::get('/boundary-commission', [AboutController::class, 'boundaryCommission'])->name('boundary-commission');
});

// Elections
Route::prefix('elections')->name('elections.')->group(function () {
    Route::get('/', [ElectionController::class, 'index'])->name('index');
    Route::get('/calendar', [ElectionController::class, 'calendar'])->name('calendar');
    Route::get('/results', [ElectionController::class, 'results'])->name('results');
    Route::get('/types', [ElectionController::class, 'types'])->name('types');
});
Route::get('/electoral-system', [ElectionController::class, 'electoralSystem'])->name('electoral-system');

// Voters
Route::prefix('voter')->name('voter.')->group(function () {
    Route::get('/', [VoterController::class, 'index'])->name('index');
    Route::get('/register', [VoterController::class, 'register'])->name('register');
    Route::post('/register', [VoterController::class, 'store'])->middleware('throttle:registration')->name('register.submit');
    Route::match(['get', 'post'], '/register/verify', [VoterController::class, 'verifyRegistrationOtp'])->middleware('throttle:otp')->name('register.verify-otp');
    Route::post('/register/verify/resend', [VoterController::class, 'resendRegistrationOtp'])->middleware('throttle:otp')->name('register.resend-otp');
    Route::get('/status', [VoterController::class, 'status'])->name('status');
    Route::post('/status', [VoterController::class, 'status'])->middleware('throttle:otp')->name('status.check');
    Route::get('/verify', [VoterController::class, 'verify'])->name('verify');
    Route::post('/verify', [VoterController::class, 'verify'])->middleware('throttle:otp')->name('verify.submit');
    Route::get('/polling-finder', [VoterController::class, 'pollingFinder'])->name('polling-finder');
    Route::post('/polling-finder', [VoterController::class, 'pollingFinder'])->name('polling-finder.search');
    Route::get('/transfer', [VoterController::class, 'transfer'])->name('transfer');
    Route::post('/transfer', [VoterController::class, 'transfer'])->name('transfer.submit');
    Route::get('/inquiry', [VoterController::class, 'inquiry'])->name('inquiry');
    Route::post('/inquiry', [VoterController::class, 'inquiry'])->name('inquiry.submit');
    Route::get('/forgot-id', [VoterController::class, 'forgotId'])->name('forgot-id');
    Route::post('/forgot-id', [VoterController::class, 'forgotId'])->middleware('throttle:otp')->name('forgot-id.submit');
    Route::get('/report-issue', [VoterController::class, 'reportIssue'])->name('report-issue');
    Route::post('/report-issue', [VoterController::class, 'reportIssue'])->middleware('throttle:registration')->name('report-issue.submit');
    Route::get('/education', [VoterController::class, 'education'])->name('education');
    Route::get('/how-to-vote', [VoterController::class, 'howToVote'])->name('how-to-vote');
    Route::get('/id-card', [VoterController::class, 'idCard'])->name('id-card');
    Route::get('/registration-success', function() {
        return view('voter.success');
    })->name('registration-success');
});

// Media
Route::prefix('media')->name('media.')->group(function () {
    Route::get('/news', [MediaController::class, 'news'])->name('news');
    Route::get('/gallery', [MediaController::class, 'gallery'])->name('gallery');
    Route::get('/videos', [MediaController::class, 'videos'])->name('videos');
    Route::get('/speeches', [MediaController::class, 'speeches'])->name('speeches');
    Route::get('/press-releases', [MediaController::class, 'pressReleases'])->name('press-releases');
    Route::get('/publications', [MediaController::class, 'publications'])->name('publications');
});
Route::get('/news/{slug}', [MediaController::class, 'article'])->name('news.article');
Route::get('/events', [\App\Http\Controllers\EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [\App\Http\Controllers\EventController::class, 'show'])->name('events.show');

// CMS pages (mirrored from nec.gov.ss and managed internally)
Route::get('/pages/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('pages.show');

// Other public
Route::get('/constituencies', [ConstituencyController::class, 'index'])->name('constituencies.index');
Route::get('/parties', [PartyController::class, 'index'])->name('parties.index');
Route::get('/candidates', [CandidateController::class, 'index'])->name('candidates.index');
Route::get('/observers', [ObserverController::class, 'index'])->name('observers.index');
Route::get('/observers/accredit', [ObserverController::class, 'accredit'])->name('observers.accredit');
Route::get('/observers/apply', [ObserverController::class, 'apply'])->name('observers.apply');
Route::post('/observers/apply', [ObserverController::class, 'applySubmit'])->name('observers.apply.submit');
Route::get('/observers/apply/success/{id}', [ObserverController::class, 'applySuccess'])->name('observers.apply.success');
Route::post('/observers/accredit', [ObserverController::class, 'accredit'])->name('observers.accredit.submit');
Route::get('/downloads', [DownloadController::class, 'index'])->name('downloads.index');
Route::get('/downloads/forms', [DownloadController::class, 'forms'])->name('downloads.forms');
Route::get('/downloads/serve/{type}/{id}', [DownloadController::class, 'serve'])->name('downloads.serve');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'index'])->name('contact.submit');
Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
Route::get('/search', [SearchController::class, 'index'])->name('search.results');
Route::get('/reports/annual', [ReportController::class, 'annual'])->name('reports.annual');
Route::get('/reports/voter-statistics', [ReportController::class, 'locationStats'])->name('reports.voter-stats');
Route::get('/reports/voter-statistics/export', [ReportController::class, 'locationStatsCsv'])->name('reports.voter-stats.export');
Route::get('/legal/privacy-policy', [LegalController::class, 'privacyPolicy'])->name('legal.privacy-policy');
Route::get('/legal/terms-of-use', [LegalController::class, 'termsOfUse'])->name('legal.terms-of-use');
Route::get('/legal/accessibility', [LegalController::class, 'accessibility'])->name('legal.accessibility');
Route::get('/legal/legal-framework', [AboutController::class, 'legalFramework'])->name('legal.legal-framework');
Route::get('/sitemap', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/help', [HelpController::class, 'index'])->name('help.index');
Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
Route::get('/gis/map', [GisController::class, 'map'])->name('gis.map');

// Unified Auth
Route::match(['get', 'post'], '/login', [AuthController::class, 'unifiedLogin'])->middleware('throttle:login')->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::match(['get', 'post'], '/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:otp')->name('forgot-password');

// Voter Portal (redirect login to unified)
Route::prefix('voter/portal')->name('voter.portal.')->group(function () {
    Route::redirect('/login', '/login')->name('login');
    Route::redirect('/forgot-password', '/forgot-password')->name('forgot-password');
    Route::get('/register', [VoterAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [VoterAuthController::class, 'register'])->middleware('throttle:registration')->name('register.submit');
    Route::match(['get', 'post'], '/register/verify', [VoterAuthController::class, 'verifyEmailOtp'])->middleware('throttle:otp')->name('register.verify-otp');
    Route::post('/register/verify/resend', [VoterAuthController::class, 'resendEmailOtp'])->middleware('throttle:otp')->name('register.resend-otp');
    Route::post('/logout', [VoterAuthController::class, 'logout'])->name('logout');
    Route::get('/verify', [VoterAuthController::class, 'verifyVoter'])->name('verify');
    Route::post('/verify', [VoterAuthController::class, 'verifyVoter'])->name('verify.submit');
});

// Voter Portal (authenticated)
Route::prefix('voter/portal')->name('voter.portal.')->middleware('voter')->group(function () {
    Route::get('/dashboard', [VoterAuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [VoterAuthController::class, 'profile'])->name('profile');
    Route::post('/profile', [VoterAuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/id-card', [VoterAuthController::class, 'idCard'])->name('id-card');
    Route::get('/transfer', [VoterAuthController::class, 'transfer'])->name('transfer');
    Route::post('/transfer', [VoterAuthController::class, 'transferSubmit'])->name('transfer.submit');
    Route::get('/transfer-status', [VoterAuthController::class, 'transferStatus'])->name('transfer-status');
});

// Newsletter (POST to API)
Route::post('/newsletter/subscribe', [App\Http\Controllers\Api\ApiNewsletterController::class, 'store'])->name('newsletter.subscribe');

// Geographic API (public, for cascading dropdowns and drill-down)
Route::prefix('api/geo')->name('api.geo.')->group(function () {
    Route::get('/countries', [GeographicController::class, 'countries'])->name('countries');
    Route::get('/diaspora-missions', [GeographicController::class, 'diasporaMissions'])->name('diaspora-missions');
    Route::get('/states', [GeographicController::class, 'states'])->name('states');
    Route::get('/counties', [GeographicController::class, 'counties'])->name('counties');
    Route::get('/constituencies', [GeographicController::class, 'constituencies'])->name('constituencies');
    Route::get('/payams', [GeographicController::class, 'payams'])->name('payams');
    Route::get('/bomas', [GeographicController::class, 'bomas'])->name('bomas');
    Route::get('/polling-stations', [GeographicController::class, 'pollingStations'])->name('polling-stations');
    Route::get('/state/{id}', [GeographicController::class, 'stateDetail'])->name('state-detail');
    Route::get('/county/{id}', [GeographicController::class, 'countyDetail'])->name('county-detail');
    Route::get('/dashboard', [GeographicController::class, 'dashboard'])->name('dashboard');
});

// Voter duplicate check API (public)
Route::post('/api/voter/check-duplicate', [VoterController::class, 'checkDuplicate'])->name('api.voter.check-duplicate');
Route::post('/api/voter/auto-save', [VoterController::class, 'autoSave'])->name('api.voter.auto-save');

// Admin login (redirect to unified)
Route::get('/admin/login', function () { return redirect('/login'); })->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'unifiedLogin'])->middleware('throttle:login')->name('admin.login.submit');
Route::redirect('/admin/forgot-password', '/forgot-password')->name('admin.forgot-password');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin (with middleware)
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // News CRUD
    Route::resource('news', AdminNewsController::class)->except(['show']);
    Route::post('news/bulk-action', [AdminNewsController::class, 'bulkAction'])->name('news.bulk-action');
    Route::get('news/{id}/toggle-status', [AdminNewsController::class, 'toggleStatus'])->name('news.toggle-status');
    Route::get('news/{id}/restore', [AdminNewsController::class, 'restore'])->name('news.restore');
    Route::delete('news/{id}/force-delete', [AdminNewsController::class, 'forceDelete'])->name('news.force-delete');

    // Events
    Route::resource('events', \App\Http\Controllers\Admin\AdminEventController::class)->except(['show']);
    Route::post('events/bulk-action', [\App\Http\Controllers\Admin\AdminEventController::class, 'bulkAction'])->name('events.bulk-action');
    Route::get('events/{id}/toggle-status', [\App\Http\Controllers\Admin\AdminEventController::class, 'toggleStatus'])->name('events.toggle-status');
    Route::get('events/{id}/restore', [\App\Http\Controllers\Admin\AdminEventController::class, 'restore'])->name('events.restore');
    Route::delete('events/{id}/force-delete', [\App\Http\Controllers\Admin\AdminEventController::class, 'forceDelete'])->name('events.force-delete');

    // Commissioners
    Route::resource('commissioners', AdminCommissionerController::class)->except(['show']);

    // Voters
    Route::get('voters', [AdminVoterController::class, 'index'])->name('voters.index');
    Route::get('voters/create', [AdminVoterController::class, 'create'])->name('voters.create');
    Route::post('voters', [AdminVoterController::class, 'store'])->name('voters.store');
    Route::get('voters/trashed', [AdminVoterController::class, 'trashed'])->name('voters.trashed');
    Route::get('voters/export', [AdminVoterController::class, 'export'])->name('voters.export');
    Route::get('voters/import/template', [AdminVoterController::class, 'importTemplate'])->name('voters.import-template');
    Route::post('voters/import', [AdminVoterController::class, 'import'])->name('voters.import');
    Route::post('voters/bulk-action', [AdminVoterController::class, 'bulkAction'])->name('voters.bulk-action');
    Route::get('voters/{voter}', [AdminVoterController::class, 'show'])->name('voters.show');
    Route::get('voters/{voter}/edit', [AdminVoterController::class, 'edit'])->name('voters.edit');
    Route::put('voters/{voter}', [AdminVoterController::class, 'update'])->name('voters.update');
    Route::patch('voters/{voter}/status', [AdminVoterController::class, 'updateStatus'])->name('voters.status');
    Route::post('voters/{voter}/deceased', [AdminVoterController::class, 'markDeceased'])->name('voters.deceased');
    Route::post('voters/{voter}/revive', [AdminVoterController::class, 'revive'])->name('voters.revive');
    Route::delete('voters/{voter}', [AdminVoterController::class, 'destroy'])->name('voters.destroy');
    Route::post('voters/{voter}/restore', [AdminVoterController::class, 'restore'])->name('voters.restore');

    // Voter Transfers
    Route::get('voter-transfers', [AdminVoterTransferController::class, 'index'])->name('voter-transfers.index');
    Route::get('voter-transfers/{transfer}', [AdminVoterTransferController::class, 'show'])->name('voter-transfers.show');
    Route::patch('voter-transfers/{transfer}/approve', [AdminVoterTransferController::class, 'approve'])->name('voter-transfers.approve');
    Route::patch('voter-transfers/{transfer}/reject', [AdminVoterTransferController::class, 'reject'])->name('voter-transfers.reject');
    Route::post('voter-transfers/bulk-action', [AdminVoterTransferController::class, 'bulkAction'])->name('voter-transfers.bulk-action');

    // Parties
    Route::put('parties/{id}/toggle-status', [AdminPartyController::class, 'toggleStatus'])->name('parties.toggle-status');
    Route::get('parties/trashed', [AdminPartyController::class, 'trashed'])->name('parties.trashed');
    Route::post('parties/{id}/restore', [AdminPartyController::class, 'restore'])->name('parties.restore');
    Route::delete('parties/{id}/force-delete', [AdminPartyController::class, 'forceDelete'])->name('parties.force-delete');
    Route::resource('parties', AdminPartyController::class);

    // Observers
    Route::get('observers', [AdminObserverController::class, 'index'])->name('observers.index');
    Route::get('observers/{observer}', [AdminObserverController::class, 'show'])->name('observers.show');
    Route::patch('observers/{observer}/status', [AdminObserverController::class, 'updateStatus'])->name('observers.status');

    // Contacts
    Route::get('contacts', [AdminContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{contact}', [AdminContactController::class, 'show'])->name('contacts.show');
    Route::patch('contacts/{contact}/status', [AdminContactController::class, 'updateStatus'])->name('contacts.status');
    Route::delete('contacts/{contact}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');

    // Announcements
    Route::resource('announcements', AdminAnnouncementController::class)->except(['show']);
    Route::post('announcements/bulk-action', [AdminAnnouncementController::class, 'bulkAction'])->name('announcements.bulk-action');
    Route::get('announcements/{id}/toggle-status', [AdminAnnouncementController::class, 'toggleStatus'])->name('announcements.toggle-status');
    Route::get('announcements/{id}/restore', [AdminAnnouncementController::class, 'restore'])->name('announcements.restore');
    Route::delete('announcements/{id}/force-delete', [AdminAnnouncementController::class, 'forceDelete'])->name('announcements.force-delete');

    // Settings
    Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::match(['post', 'put'], 'settings', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::post('settings/tool', [AdminSettingController::class, 'tool'])->name('settings.tool');

    // Users
    Route::resource('users', AdminUserController::class)->except(['show']);
    Route::get('users/trashed', [AdminUserController::class, 'trashed'])->name('users.trashed');
    Route::post('users/{user}/restore', [AdminUserController::class, 'restore'])->name('users.restore');
    Route::delete('users/{user}/force-delete', [AdminUserController::class, 'forceDelete'])->name('users.force-delete');
    Route::post('users/bulk-action', [AdminUserController::class, 'bulkAction'])->name('users.bulk-action');
    Route::patch('users/{user}/status', [AdminUserController::class, 'updateStatus'])->name('users.status');
    Route::post('users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');

    // Staff
    Route::get('staff', [AdminStaffController::class, 'index'])->name('staff.index');
    Route::get('staff/create', [AdminStaffController::class, 'create'])->name('staff.create');
    Route::post('staff', [AdminStaffController::class, 'store'])->name('staff.store');
    Route::get('staff/{staff}', [AdminStaffController::class, 'show'])->name('staff.show');
    Route::get('staff/{staff}/edit', [AdminStaffController::class, 'edit'])->name('staff.edit');
    Route::put('staff/{staff}', [AdminStaffController::class, 'update'])->name('staff.update');
    Route::patch('staff/{staff}/assign', [AdminStaffController::class, 'assign'])->name('staff.assign');
    Route::patch('staff/{staff}/status', [AdminStaffController::class, 'updateStatus'])->name('staff.status');
    Route::delete('staff/{staff}', [AdminStaffController::class, 'destroy'])->name('staff.destroy');
    Route::get('staff/{staff}/activity', [AdminStaffController::class, 'activity'])->name('staff.activity');

    // Activity Logs
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('activity-logs/{log}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
    Route::get('activity-logs/export', [ActivityLogController::class, 'export'])->name('activity-logs.export');
    Route::delete('activity-logs/{log}', [ActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
    Route::post('activity-logs/clear', [ActivityLogController::class, 'clear'])->name('activity-logs.clear');

    // Complaints
    Route::get('complaints', [AdminComplaintController::class, 'index'])->name('complaints.index');
    Route::get('complaints/{complaint}', [AdminComplaintController::class, 'show'])->name('complaints.show');
    Route::patch('complaints/{complaint}/status', [AdminComplaintController::class, 'updateStatus'])->name('complaints.status');

    // Reports
    Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::post('notifications/read-all', [AdminNotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('notifications/{notification}/read', [AdminNotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('reports', [AdminReportController::class, 'store'])->name('reports.store');
    Route::delete('reports/{report}', [AdminReportController::class, 'destroy'])->name('reports.destroy');
    Route::post('reports/{id}/restore', [AdminReportController::class, 'restore'])->name('reports.restore');

    // Polling Staff
    Route::get('polling-staff', [AdminPollingStaffController::class, 'index'])->name('polling-staff.index');
    Route::get('polling-staff/create', [AdminPollingStaffController::class, 'create'])->name('polling-staff.create');
    Route::post('polling-staff', [AdminPollingStaffController::class, 'store'])->name('polling-staff.store');
    Route::get('polling-staff/{pollingStaff}/edit', [AdminPollingStaffController::class, 'edit'])->name('polling-staff.edit');
    Route::put('polling-staff/{pollingStaff}', [AdminPollingStaffController::class, 'update'])->name('polling-staff.update');
    Route::delete('polling-staff/{pollingStaff}', [AdminPollingStaffController::class, 'destroy'])->name('polling-staff.destroy');
    Route::patch('polling-staff/{pollingStaff}/status', [AdminPollingStaffController::class, 'updateStatus'])->name('polling-staff.status');

    // Ballots
    Route::get('ballots', [AdminBallotController::class, 'index'])->name('ballots.index');
    Route::get('ballots/create', [AdminBallotController::class, 'create'])->name('ballots.create');
    Route::post('ballots', [AdminBallotController::class, 'store'])->name('ballots.store');
    Route::get('ballots/{ballot}/edit', [AdminBallotController::class, 'edit'])->name('ballots.edit');
    Route::put('ballots/{ballot}', [AdminBallotController::class, 'update'])->name('ballots.update');
    Route::delete('ballots/{ballot}', [AdminBallotController::class, 'destroy'])->name('ballots.destroy');

    // Election Petitions
    Route::get('petitions', [AdminPetitionController::class, 'index'])->name('petitions.index');
    Route::get('petitions/create', [AdminPetitionController::class, 'create'])->name('petitions.create');
    Route::post('petitions', [AdminPetitionController::class, 'store'])->name('petitions.store');
    Route::get('petitions/{petition}/edit', [AdminPetitionController::class, 'edit'])->name('petitions.edit');
    Route::put('petitions/{petition}', [AdminPetitionController::class, 'update'])->name('petitions.update');
    Route::delete('petitions/{petition}', [AdminPetitionController::class, 'destroy'])->name('petitions.destroy');

    // Security Logs
    Route::get('security-logs', [AdminSecurityLogController::class, 'index'])->name('security-logs.index');
    Route::get('security-logs/{securityLog}', [AdminSecurityLogController::class, 'show'])->name('security-logs.show');
    Route::delete('security-logs/{securityLog}', [AdminSecurityLogController::class, 'destroy'])->name('security-logs.destroy');
    Route::post('security-logs/clear', [AdminSecurityLogController::class, 'clear'])->name('security-logs.clear');

    // Downloads
    Route::get('downloads', [AdminDownloadController::class, 'index'])->name('downloads.index');
    Route::get('downloads/create', [AdminDownloadController::class, 'create'])->name('downloads.create');
    Route::post('downloads', [AdminDownloadController::class, 'store'])->name('downloads.store');
    Route::get('downloads/{download}/edit', [AdminDownloadController::class, 'edit'])->name('downloads.edit');
    Route::put('downloads/{download}', [AdminDownloadController::class, 'update'])->name('downloads.update');
    Route::delete('downloads/{download}', [AdminDownloadController::class, 'destroy'])->name('downloads.destroy');

    // Constituencies
    Route::resource('constituencies', AdminConstituencyController::class)->except(['show']);

    // Candidates
    Route::put('candidates/{id}/toggle-status', [AdminCandidateController::class, 'toggleStatus'])->name('candidates.toggle-status');
    Route::get('candidates/trashed', [AdminCandidateController::class, 'trashed'])->name('candidates.trashed');
    Route::post('candidates/{id}/restore', [AdminCandidateController::class, 'restore'])->name('candidates.restore');
    Route::delete('candidates/{id}/force-delete', [AdminCandidateController::class, 'forceDelete'])->name('candidates.force-delete');
    Route::resource('candidates', AdminCandidateController::class);

    // Results
    Route::put('results/{id}/toggle-status', [AdminResultController::class, 'toggleStatus'])->name('results.toggle-status');
    Route::resource('results', AdminResultController::class);

    // Gallery
    Route::resource('gallery', AdminGalleryController::class)->except(['show']);
    Route::post('gallery/bulk-action', [AdminGalleryController::class, 'bulkAction'])->name('gallery.bulk-action');
    Route::get('gallery/{id}/toggle-status', [AdminGalleryController::class, 'toggleStatus'])->name('gallery.toggle-status');
    Route::get('gallery/{id}/restore', [AdminGalleryController::class, 'restore'])->name('gallery.restore');
    Route::delete('gallery/{id}/force-delete', [AdminGalleryController::class, 'forceDelete'])->name('gallery.force-delete');

    // Speeches
    Route::resource('speeches', AdminSpeechController::class)->except(['show']);
    Route::post('speeches/bulk-action', [AdminSpeechController::class, 'bulkAction'])->name('speeches.bulk-action');
    Route::get('speeches/{id}/toggle-status', [AdminSpeechController::class, 'toggleStatus'])->name('speeches.toggle-status');
    Route::get('speeches/{id}/restore', [AdminSpeechController::class, 'restore'])->name('speeches.restore');
    Route::delete('speeches/{id}/force-delete', [AdminSpeechController::class, 'forceDelete'])->name('speeches.force-delete');

    // Videos
    Route::resource('videos', AdminVideoController::class)->except(['show']);

    // FAQs
    Route::resource('faqs', AdminFaqController::class)->except(['show']);
    Route::post('faqs/reorder', [AdminFaqController::class, 'reorder'])->name('faqs.reorder');

    // Polling Stations
    Route::get('polling-stations/counties', [AdminPollingStationController::class, 'getCounties'])->name('polling-stations.counties');
    Route::get('polling-stations/constituencies', [AdminPollingStationController::class, 'getConstituencies'])->name('polling-stations.constituencies');
    Route::get('polling-stations/generate-code', [AdminPollingStationController::class, 'generateCode'])->name('polling-stations.generate-code');
    Route::resource('polling-stations', AdminPollingStationController::class)->except(['show']);

    // Geographic Management
    Route::get('geographic', [\App\Http\Controllers\Admin\AdminGeographicController::class, 'index'])->name('geographic.index');
    Route::get('geographic/overview', [\App\Http\Controllers\Admin\AdminGeographicController::class, 'overview'])->name('geographic.overview');
    Route::get('geographic/states/{state}', [\App\Http\Controllers\Admin\AdminGeographicController::class, 'state'])->name('geographic.state');
    Route::post('geographic/states', [\App\Http\Controllers\Admin\AdminGeographicController::class, 'storeState'])->name('geographic.state.store');
    Route::put('geographic/states/{state}', [\App\Http\Controllers\Admin\AdminGeographicController::class, 'updateState'])->name('geographic.state.update');
    Route::delete('geographic/states/{state}', [\App\Http\Controllers\Admin\AdminGeographicController::class, 'destroyState'])->name('geographic.state.destroy');
    Route::post('geographic/counties', [\App\Http\Controllers\Admin\AdminGeographicController::class, 'storeCounty'])->name('geographic.county.store');
    Route::put('geographic/counties/{county}', [\App\Http\Controllers\Admin\AdminGeographicController::class, 'updateCounty'])->name('geographic.county.update');
    Route::delete('geographic/counties/{county}', [\App\Http\Controllers\Admin\AdminGeographicController::class, 'destroyCounty'])->name('geographic.county.destroy');
    Route::post('geographic/constituencies', [\App\Http\Controllers\Admin\AdminGeographicController::class, 'storeConstituency'])->name('geographic.constituency.store');
    Route::put('geographic/constituencies/{constituency}', [\App\Http\Controllers\Admin\AdminGeographicController::class, 'updateConstituency'])->name('geographic.constituency.update');
    Route::delete('geographic/constituencies/{constituency}', [\App\Http\Controllers\Admin\AdminGeographicController::class, 'destroyConstituency'])->name('geographic.constituency.destroy');
    Route::post('geographic/payams', [\App\Http\Controllers\Admin\AdminGeographicController::class, 'storePayam'])->name('geographic.payam.store');
    Route::put('geographic/payams/{payam}', [\App\Http\Controllers\Admin\AdminGeographicController::class, 'updatePayam'])->name('geographic.payam.update');
    Route::delete('geographic/payams/{payam}', [\App\Http\Controllers\Admin\AdminGeographicController::class, 'destroyPayam'])->name('geographic.payam.destroy');
    Route::post('geographic/bomas', [\App\Http\Controllers\Admin\AdminGeographicController::class, 'storeBoma'])->name('geographic.boma.store');
    Route::put('geographic/bomas/{boma}', [\App\Http\Controllers\Admin\AdminGeographicController::class, 'updateBoma'])->name('geographic.boma.update');
    Route::delete('geographic/bomas/{boma}', [\App\Http\Controllers\Admin\AdminGeographicController::class, 'destroyBoma'])->name('geographic.boma.destroy');

    // Countries (worldwide voter reference)
    Route::get('countries', [AdminCountryController::class, 'index'])->name('countries.index');
    Route::get('countries/create', [AdminCountryController::class, 'create'])->name('countries.create');
    Route::post('countries', [AdminCountryController::class, 'store'])->name('countries.store');
    Route::get('countries/{country}/edit', [AdminCountryController::class, 'edit'])->name('countries.edit');
    Route::put('countries/{country}', [AdminCountryController::class, 'update'])->name('countries.update');
    Route::get('countries/{country}/toggle-status', [AdminCountryController::class, 'toggleStatus'])->name('countries.toggle-status');
    Route::delete('countries/{country}', [AdminCountryController::class, 'destroy'])->name('countries.destroy');

    // Diaspora Missions
    Route::get('diaspora-missions', [AdminDiasporaMissionController::class, 'index'])->name('diaspora-missions.index');
    Route::get('diaspora-missions/create', [AdminDiasporaMissionController::class, 'create'])->name('diaspora-missions.create');
    Route::post('diaspora-missions', [AdminDiasporaMissionController::class, 'store'])->name('diaspora-missions.store');
    Route::get('diaspora-missions/{mission}/edit', [AdminDiasporaMissionController::class, 'edit'])->name('diaspora-missions.edit');
    Route::put('diaspora-missions/{mission}', [AdminDiasporaMissionController::class, 'update'])->name('diaspora-missions.update');
    Route::get('diaspora-missions/{mission}/toggle-status', [AdminDiasporaMissionController::class, 'toggleStatus'])->name('diaspora-missions.toggle-status');
    Route::get('diaspora-missions/{mission}/restore', [AdminDiasporaMissionController::class, 'restore'])->name('diaspora-missions.restore');
    Route::delete('diaspora-missions/{mission}', [AdminDiasporaMissionController::class, 'destroy'])->name('diaspora-missions.destroy');

    // Subscribers
    Route::get('subscribers', [AdminSubscriberController::class, 'index'])->name('subscribers.index');
    Route::get('subscribers/export', [AdminSubscriberController::class, 'export'])->name('subscribers.export');
    Route::delete('subscribers/{subscriber}', [AdminSubscriberController::class, 'destroy'])->name('subscribers.destroy');

    // Education Materials
    Route::resource('education', AdminEducationController::class)->except(['show']);
    Route::post('education/bulk-action', [AdminEducationController::class, 'bulkAction'])->name('education.bulk-action');
    Route::get('education/{id}/toggle-status', [AdminEducationController::class, 'toggleStatus'])->name('education.toggle-status');
    Route::get('education/{id}/restore', [AdminEducationController::class, 'restore'])->name('education.restore');
    Route::delete('education/{id}/force-delete', [AdminEducationController::class, 'forceDelete'])->name('education.force-delete');

    // Permissions
    Route::get('permissions', [\App\Http\Controllers\Admin\PermissionController::class, 'index'])->name('permissions.index');
    Route::put('permissions/{role}', [\App\Http\Controllers\Admin\PermissionController::class, 'update'])->name('permissions.update');
    Route::get('permissions/sync', [\App\Http\Controllers\Admin\PermissionController::class, 'sync'])->name('permissions.sync');

    // Registration Agents
    Route::get('agents/trashed', [AgentController::class, 'trashed'])->name('agents.trashed');
    Route::post('agents/{id}/restore', [AgentController::class, 'restore'])->name('agents.restore');
    Route::delete('agents/{id}/force-delete', [AgentController::class, 'forceDelete'])->name('agents.force-delete');
    Route::resource('agents', AgentController::class)->except(['show']);
    Route::patch('agents/{agent}/status', [AgentController::class, 'updateStatus'])->name('agents.status');
    Route::get('agents/{agent}/voters', [AgentController::class, 'voters'])->name('agents.voters');
});
