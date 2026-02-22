<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| 1. IMPORT CONTROLLER
|--------------------------------------------------------------------------
*/

// Auth
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\PublicController; // <--- PENTING

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\ProjectController as AdminProject;
use App\Http\Controllers\Admin\ReportController as AdminReport;
use App\Http\Controllers\Admin\NewsController as AdminNews;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolio;
use App\Http\Controllers\Admin\TeamController; 

// Eco Controllers (Divisi Beras)
use App\Http\Controllers\Eco\DashboardController as EcoDashboard;
use App\Http\Controllers\Eco\NewsController as EcoNews;
use App\Http\Controllers\Eco\PortofolioController as EcoPortfolio;
use App\Http\Controllers\Eco\VisitPlanController;
use App\Http\Controllers\Eco\PlasticStockController;
use App\Http\Controllers\Eco\SoldRiceController;
use App\Http\Controllers\Eco\StorePartnerController;
use App\Http\Controllers\Eco\MillingReportController;
use App\Http\Controllers\Eco\StoreRiceStockController;
use App\Http\Controllers\Eco\VisitResultController;

// Subkon & Keuangan
use App\Http\Controllers\SubkonPT\DashboardController as SubkonPTDashboard;
use App\Http\Controllers\SubkonEks\DashboardController as SubkonEksDashboard;
use App\Http\Controllers\SubkonEks\ReportController as SubkonEksReport;
use App\Http\Controllers\Keuangan\DashboardController as KeuanganController;
use App\Http\Controllers\SubkonEks\ReportPaymentController as ReportPayment;

// Indie
use App\Http\Controllers\Indie\DashboardController as IndieDashboard;
use App\Http\Controllers\Indie\NewsController as IndieNews;
use App\Http\Controllers\Indie\PortofolioController as IndiePortfolio;

// Umum
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\LoginHistoryController;
use App\Models\Portfolio;

/*
|--------------------------------------------------------------------------
| 2. PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Halaman Utama (Home) -> Menggunakan PublicController agar $teams terkirim
Route::get('/', [PublicController::class, 'welcome'])->name('welcome');

// Halaman Portofolio
Route::get('/portofolio', function () {
    $portfolios = Portfolio::latest()->get(); 
    return view('portfolio', compact('portfolios'));
})->name('portfolio');

// Halaman Berita
Route::get('/berita', [PublicController::class, 'index'])->name('components.berita');
Route::get('/berita/{id}', [PublicController::class, 'show'])->name('components.berita.detail');

/*
|--------------------------------------------------------------------------
| 3. AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    
    // Password Reset
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| 4. DASHBOARD ROUTES (ROLE BASED)
|--------------------------------------------------------------------------
*/

// A. GROUP ADMIN (Pusat)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('users', AdminUser::class);
    Route::resource('projects', AdminProject::class);
    Route::get('/reports/export', [AdminReport::class, 'exportAll'])->name('reports.export');
    
    // Modul Tambahan
    Route::resource('locations', App\Http\Controllers\Admin\EcoLocationController::class);
    Route::resource('news', AdminNews::class);
    Route::resource('portfolios', AdminPortfolio::class);
    Route::resource('teams', TeamController::class); // <--- CRUD Team
   

});

// B. GROUP ECO (Divisi Beras)
Route::middleware(['auth', 'role:eco'])->prefix('eco')->name('eco.')->group(function () {
    Route::get('/dashboard', [EcoDashboard::class, 'index'])->name('dashboard');
    Route::post('/update-stock', [EcoDashboard::class, 'updateStock'])->name('stock.update');
    Route::resource('news', EcoNews::class);
   Route::get('/reports/export', [EcoDashboard::class, 'exportReport'])->name('reports.export');
    Route::get('/export-log', [EcoDashboard::class, 'exportLog'])->name('stock.export');
    Route::resource('portfolios', EcoPortfolio::class);
    Route::resource('visit-plans', VisitPlanController::class)->except(['create', 'edit', 'update', 'show']);
    Route::get('visit-plans/export/pdf', [VisitPlanController::class, 'exportPdf'])->name('visit-plans.export');

    Route::resource('plastic-stocks', PlasticStockController::class)->except(['create', 'edit', 'update', 'show']);
    Route::get('plastic-stocks/export/pdf', [PlasticStockController::class, 'exportPdf'])->name('plastic-stocks.export');

    Route::resource('sold-rices', SoldRiceController::class)->except(['create', 'edit', 'update', 'show']);
    Route::get('sold-rices/export/pdf', [SoldRiceController::class, 'exportPdf'])->name('sold-rices.export');

    Route::resource('store-partners', StorePartnerController::class)->except(['create', 'edit', 'update', 'show']);
    Route::get('store-partners/export/pdf', [StorePartnerController::class, 'exportPdf'])->name('store-partners.export');
Route::resource('milling-reports', MillingReportController::class)->except(['create', 'edit', 'update', 'show']);
    Route::get('milling-reports/export/pdf', [MillingReportController::class, 'exportPdf'])->name('milling-reports.export');
Route::resource('store-rice-stocks', StoreRiceStockController::class)->except(['create', 'edit', 'update', 'show']);
    Route::get('store-rice-stocks/export/pdf', [StoreRiceStockController::class, 'exportPdf'])->name('store-rice-stocks.export');
Route::resource('visit-results', VisitResultController::class)->except(['create', 'edit', 'update', 'show']);
Route::get('visit-results/export/excel', [VisitResultController::class, 'exportExcel'])->name('visit-results.export');
});

// C. GROUP INDIE
Route::middleware(['auth', 'role:indie'])->prefix('indie')->name('indie.')->group(function () {
    Route::get('/dashboard', [IndieDashboard::class, 'index'])->name('dashboard');
    Route::resource('news', IndieNews::class);
    Route::get('/export-projects', [IndieDashboard::class, 'exportPdf'])->name('projects.export');
    Route::resource('portfolios', IndiePortfolio::class);
});

// D. GROUP SUBKON & KEUANGAN
Route::middleware(['auth', 'role:subkon_pt'])->prefix('internal')->name('subkon-pt.')->group(function () {
    Route::get('/dashboard', [SubkonPTDashboard::class, 'index'])->name('dashboard');
    Route::get('/penilaian/create/{target_id}', [RatingController::class, 'create'])->name('rating.create');
    Route::post('/penilaian', [RatingController::class, 'store'])->name('rating.store');
    Route::get('/monitoring', [ProjectController::class, 'monitoring'])->name('monitoring');
    Route::patch('/laporan/{id}/status', [SubkonPTDashboard::class, 'updateStatus'])->name('reports.status');
});

Route::middleware(['auth', 'role:subkon_eks'])->prefix('vendor')->name('subkon-eks.')->group(function () {
    Route::get('/dashboard', [SubkonEksDashboard::class, 'index'])->name('dashboard');
    Route::resource('reports', SubkonEksReport::class);
    Route::resource('report-payments', ReportPayment::class);
    Route::get('/penilaian/cetak/{id}', [RatingController::class, 'printPDF'])->name('rating.print');
    Route::get('/laporan/{id}/cetak', [SubkonEksReport::class, 'print'])->name('reports.print');
});

Route::middleware(['auth', 'role:keuangan_indie'])->prefix('finance')->name('keuangan.')->group(function () {
    Route::get('/dashboard', [KeuanganController::class, 'index'])->name('dashboard');
    Route::put('/laporan/{id}/verifikasi', [KeuanganController::class, 'verifyReport'])->name('reports.verify');
});

// E. GENERAL (Semua User Login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/api/user-detail/{id}', [AdminUser::class, 'showApi'])->name('api.user.detail');
    Route::get('/riwayat-login', [LoginHistoryController::class, 'index'])->name('login-history.index');
});

//KEUANGAN ECO
Route::middleware(['auth', 'role:keuangan_eco'])->prefix('keuangan-eco')->name('keuangan_eco.')->group(function () {
    Route::get('/visit-results', [VisitResultController::class, 'indexKeuangan'])->name('visit-results.index');
    Route::get('/visit-results/export/excel', [VisitResultController::class, 'exportExcel'])->name('visit-results.export');
});



// ... kode-kode route lainnya ...

// === TAMBAHKAN INI DI BARIS PALING BAWAH ===

Route::get('/portal-masuk', function () {
    // 1. Cek: Apakah user SUDAH login?
    if (Illuminate\Support\Facades\Auth::check()) {
        $role = Illuminate\Support\Facades\Auth::user()->role;
        
        // Jika SUDAH login, langsung lempar ke dashboard masing-masing
        if ($role == 'admin') return redirect()->route('admin.dashboard');
        if ($role == 'eco') return redirect()->route('eco.dashboard');
        if ($role == 'indie') return redirect()->route('indie.dashboard');
        if ($role == 'subkon_pt') return redirect()->route('subkon-pt.dashboard');
        if ($role == 'subkon_eks') return redirect()->route('subkon-eks.dashboard');
        
        // Role Keuangan
        if ($role == 'keuangan_eco') return redirect()->route('keuangan_eco.visit-results.index');
        if ($role == 'keuangan' || $role == 'keuangan_indie') return redirect()->route('keuangan.dashboard');

        // Role yang belum punya dashboard
        if ($role == 'kepala_kantor' || $role == 'manager_unit') return redirect('/'); 
        
        return redirect('/'); // Default
    } 
    
    // 2. Jika BELUM login, tampilkan halaman Login
    return view('auth.login'); 

})->name('portal.masuk');


// ====================================================
// 2. GRUP KEPALA KANTOR (Baru)
// ====================================================
Route::middleware(['auth', 'role:kepala_kantor'])->prefix('kepala-kantor')->name('kepala_kantor.')->group(function () {
    // Dashboard sekaligus halaman input LH
    Route::get('/dashboard', [App\Http\Controllers\KepalaKantor\LhController::class, 'index'])->name('dashboard');
    Route::post('/lh', [App\Http\Controllers\KepalaKantor\LhController::class, 'store'])->name('lh.store');

    Route::put('/lh/{id}', [App\Http\Controllers\KepalaKantor\LhController::class, 'update'])->name('lh.update');
    Route::delete('/lh/{id}', [App\Http\Controllers\KepalaKantor\LhController::class, 'destroy'])->name('lh.destroy');
});


// ====================================================
// 3. GRUP MANAGER UNIT (Baru - Download Center)
// ====================================================
Route::middleware(['auth', 'role:manager_unit'])->prefix('manager-unit')->name('manager_unit.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\ManagerUnit\DownloadController::class, 'dashboard'])->name('dashboard');
    // LHKP
    Route::resource('lhkp', \App\Http\Controllers\ManagerUnit\LhkpController::class)->except(['create', 'edit', 'show']);

    // Route Download
    Route::get('/lhkp/{id}/pdf', [App\Http\Controllers\ManagerUnit\DownloadController::class, 'downloadLhkp'])->name('lhkp.pdf');
    Route::get('/lh/{id}/pdf', [App\Http\Controllers\ManagerUnit\DownloadController::class, 'downloadLh'])->name('lh.pdf');
});