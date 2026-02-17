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
use App\Http\Controllers\Admin\TeamController; // <--- Team Controller

// Eco Controllers (Divisi Beras)
use App\Http\Controllers\Eco\DashboardController as EcoDashboard;
use App\Http\Controllers\Eco\NewsController as EcoNews;
use App\Http\Controllers\Eco\PortofolioController as EcoPortfolio;

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

Route::middleware(['auth', 'role:keuangan'])->prefix('finance')->name('keuangan.')->group(function () {
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