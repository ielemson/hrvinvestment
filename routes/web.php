<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FrontPagesController;
use App\Http\Controllers\ContactController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\KycController;
use App\Http\Controllers\User\LoanKycController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\User\UserLoanController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminKycController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\LoanReviewController;

/*
|--------------------------------------------------------------------------
| Public (Front Pages)
|--------------------------------------------------------------------------
*/

Route::controller(FrontPagesController::class)->group(function () {
    Route::get('/', 'home')->name('home');

    Route::get('/about', 'about')->name('about');

    Route::get('/services', 'services')->name('services.index');
    Route::get('/services/{service:slug}', 'showservice')->name('services.show');

    Route::get('/blog', 'blog')->name('blog');
    Route::get('/how-it-works', 'howitworks')->name('how-it-works');

    Route::get('/contact', 'contact')->name('contact');
});

/** Single contact POST (remove duplicates) */
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.send');

/*
|--------------------------------------------------------------------------
| Authenticated: Role-aware Dashboard Resolver + Notifications
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /** Option A: /dashboard always redirects by role */
    Route::get('/dashboard', function () {
        $user = Auth::user();

        if ($user?->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.dashboard');
    })->name('dashboard');

    /** Notifications */
    Route::get('/notifications', function () {
        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        return view('notifications.index', compact('notifications'));
    })->name('notifications.index');

    Route::post('/notifications/{id}/read', function ($id) {
        $n = auth()->user()->notifications()->where('id', $id)->firstOrFail();
        $n->markAsRead();
        return back();
    })->name('notifications.read');
});

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::prefix('user')
    ->name('user.')
    ->middleware(['auth', 'verified'])
    ->group(function () {

        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

        /** KYC */
        Route::get('/kyc', [KycController::class, 'edit'])->name('kyc.edit');
        Route::post('/kyc', [KycController::class, 'update'])->name('kyc.update');

        /** Profile (remove duplicate /profile route; fix typo proile.show) */
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::patch('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
        Route::patch('/profile/preferences', [ProfileController::class, 'preferences'])->name('profile.preferences');
        Route::patch('/profile/avatar', [ProfileController::class, 'avatar'])->name('profile.avatar');

        /** User Loans */
        Route::get('/loans/view', [UserLoanController::class, 'index'])->name('loans.index');
        Route::get('/loans/create', [UserLoanController::class, 'create'])->name('loans.create');
        Route::post('/loans/store', [UserLoanController::class, 'store'])->name('loans.store');
        Route::get('/loans/view/{loan}', [UserLoanController::class, 'show'])->name('loans.show');

        /** Apply */
        Route::post('/apply', [LoanKycController::class, 'store'])->name('apply.store');
    });




/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        /** Dashboard */
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        /** KYC */
        Route::get('/kyc', [AdminKycController::class, 'index'])->name('kyc.index');
        Route::get('/kyc/{kyc}', [AdminKycController::class, 'show'])->name('kyc.show');
        Route::post('/kyc/{kyc}/approve', [AdminKycController::class, 'approve'])->name('kyc.approve');
        Route::post('/kyc/{kyc}/reject', [AdminKycController::class, 'reject'])->name('kyc.reject');

        /** Settings */
        Route::get('/settings', [SiteSettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SiteSettingController::class, 'update'])->name('settings.update');

        /** Management */
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::resource('services', ServiceController::class)->except(['show']);

        /** Sliders */
        Route::resource('sliders', SliderController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        /** Loan Review Workflow */
        Route::get('/loans', [LoanReviewController::class, 'index'])->name('loans.index');
        Route::get('/loans/{loan}', [LoanReviewController::class, 'show'])->name('loans.show');
        Route::post('/loans/{loan}/approve', [LoanReviewController::class, 'approve'])->name('loans.approve');
        Route::post('/loans/{loan}/reject', [LoanReviewController::class, 'reject'])->name('loans.reject');
        Route::post('/loans/{loan}/status', [LoanReviewController::class, 'setStatus'])->name('loans.status');
        Route::post('/loans/{loan}/workflow', [LoanReviewController::class, 'updateWorkflow'])
            ->name('loans.workflow.update');

        /** Disbursement (fix double /admin prefix) */
        Route::post('/loans/{loan}/disburse', [LoanController::class, 'disburse'])->name('loans.disburse');
    });

require __DIR__ . '/auth.php';
