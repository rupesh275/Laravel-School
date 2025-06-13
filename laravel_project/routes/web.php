<?php

use Illuminate\Support\Facades\Route;
// Assuming controllers will be in these namespaces - adjust if needed
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\PageController; // For CMS pages
use App\Http\Controllers\OnlineAdmissionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\StaffController as AdminStaffController;
use App\Http\Controllers\Admin\AdminController as AdminAreaController; // For general admin tasks like profile
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\BackupController; // Added this line
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Parent\DashboardController as ParentDashboardController;
use App\Http\Controllers\SiteController; // For things like password reset from CI's 'site' controller
use App\Http\Controllers\SearchController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// === Public Frontend / CMS Routes ===
Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('cms.page');
// CI: $route['online_admission'] = 'welcome/admission';
Route::get('/online-admission', [OnlineAdmissionController::class, 'create'])->name('online.admission.create');
Route::post('/online-admission', [OnlineAdmissionController::class, 'store'])->name('online.admission.store');
// CI: $route['user/resetpassword/([a-z]+)/(:any)'] = 'site/resetpassword/$1/$2';
Route::match(['get', 'post'], '/user/resetpassword/{token_type}/{token}', [SiteController::class, 'resetPassword'])->where('token_type', '[a-z]+')->name('password.reset.user');


// === Admin Routes ===
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin' /*, SetLocale::class, LicensingMiddleware::class (example) */])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CI: $route['admin/resetpassword/(:any)'] = 'site/admin_resetpassword/$1'; (Handled by Breeze or custom admin password reset)
    // Route::get('/profile', [AdminAreaController::class, 'profile'])->name('profile');
    // Route::post('/profile', [AdminAreaController::class, 'updateProfile'])->name('profile.update');

    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    // Admin Profile & Password
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    // Backup Management
    Route::get('/backups', [BackupController::class, 'index'])->name('backups.index');
    Route::post('/backups/create', [BackupController::class, 'create'])->name('backups.create');
    Route::get('/backups/download/{filename}', [BackupController::class, 'download'])->name('backups.download');
    Route::delete('/backups/delete/{filename}', [BackupController::class, 'destroy'])->name('backups.destroy');
    Route::get('/backups/restore', [BackupController::class, 'showRestoreForm'])->name('backups.restore.form');
    Route::post('/backups/restore', [BackupController::class, 'uploadRestore'])->name('backups.restore.upload');

    // Example Resource Controllers (many more will be needed)
    // Route::resource('students', AdminStudentController::class);
    // Route::resource('staff', AdminStaffController::class);

    // CI: $route['admin/unauthorized'] = 'admin/admin/unauthorized'; (Handled by middleware exceptions)
});

// === Student Routes ===
Route::prefix('student')->name('student.')->middleware(['auth', 'role:student' /*, SetLocale::class */])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    // Route::get('/profile', [StudentProfileController::class, 'show'])->name('profile.show');
    // Route::post('/profile', [StudentProfileController::class, 'update'])->name('profile.update');
    // ... other student routes ...
});

// === Parent Routes ===
Route::prefix('parent')->name('parent.')->middleware(['auth', 'role:parent' /*, SetLocale::class */])->group(function () {
    Route::get('/dashboard', [ParentDashboardController::class, 'index'])->name('dashboard');
    // ... other parent routes ...
});

// === Teacher, Accountant, Librarian routes would follow a similar pattern ===

// Cron route (example, might need to be in console commands or a specific controller)
// CI: $route['cron/(:any)'] = 'cron/index/$1';
// Route::get('/cron/{task}', [CronController::class, 'run'])->name('cron.run');

Route::match(['get', 'post'], '/search', [SearchController::class, 'search'])->name('search');

// Include Breeze auth routes (login, register, password reset etc.)
// This should be at the end or as per Breeze documentation.
// require __DIR__.'/auth.php'; // Ensure Breeze auth routes are present (Commented out as Breeze is not installed yet)
