<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontPagesController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\LoanKycController;
use App\Http\Controllers\User\KycController;
use App\Http\Controllers\Admin\AdminKycController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\ServicePublicController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\Admin\LoanReviewController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });




Route::controller(FrontPagesController::class)->group(function () {
    Route::get('/', 'home')->name('home');

    Route::get('/about', 'about')->name('about');
    Route::get('/services', 'services')->name('services.index');
    Route::get('/services/{service:slug}', 'showservice')->name('services.show');

    Route::get('/blog', 'blog')->name('blog');
    Route::get('/how-it-works', 'howitworks')->name('how-it-works');

    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit')->name('contact.submit'); // optional


});

Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'submit'])
    ->name('contact.submit');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// Borrower dashboard
// Route::middleware(['auth', 'verified', 'role:user'])->group(function () {

//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');

//     // other user-only routes here
//     // USER KYC
//      Route::get('/kyc', [KycController::class, 'edit'])->name('kyc.edit');
//     Route::post('/kyc', [KycController::class, 'update'])->name('kyc.update');
// });



Route::prefix('user')
    ->name('user.')
    ->middleware(['auth', 'verified', 'role:user'])
    ->group(function () {

        // Dashboard
        Route::get('dashboard', [UserDashboardController::class, 'index'])
            ->name('dashboard');

        // KYC
        Route::get('/kyc', [KycController::class, 'edit'])->name('kyc.edit');
        Route::post('/kyc', [KycController::class, 'update'])->name('kyc.update');

        // User Profile
        //  Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile', [UserDashboardController::class, 'show'])->name('proile.show');

        // USER LOANS
        Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
        Route::get('/loans/create', [LoanController::class, 'create'])->name('loans.create');
        Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
        Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');



        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::patch('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
        Route::patch('/profile/preferences', [ProfileController::class, 'preferences'])->name('profile.preferences');
        Route::patch('/profile/avatar', [ProfileController::class, 'avatar'])->name('profile.avatar');

        // Route::post('/user/apply', [\App\Http\Controllers\User\LoanKycController::class, 'store'])
        //     ->name('user.apply.store');
        Route::post('/user/apply', [LoanKycController::class, 'store'])->name('apply.store');
    });



Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        /*
        |--------------------------------------------------
        | Dashboard
        |--------------------------------------------------
        */
        // Route::get('/dashboard', function () {
        //     return view('admin.dashboard');
        // })->name('dashboard');

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        /*
        |--------------------------------------------------
        | KYC Management
        |--------------------------------------------------
        */
        Route::get('/kyc', [AdminKycController::class, 'index'])
            ->name('kyc.index');

        Route::get('/kyc/{kyc}', [AdminKycController::class, 'show'])
            ->name('kyc.show');

        Route::post('/kyc/{kyc}/approve', [AdminKycController::class, 'approve'])
            ->name('kyc.approve');

        Route::post('/kyc/{kyc}/reject', [AdminKycController::class, 'reject'])
            ->name('kyc.reject');

        /*
        |--------------------------------------------------
        | Site Settings
        |--------------------------------------------------
        */
        Route::get('/settings', [SiteSettingController::class, 'edit'])
            ->name('settings.edit');

        Route::put('/settings', [SiteSettingController::class, 'update'])
            ->name('settings.update');

        /*
        |--------------------------------------------------
        | User Management
        |--------------------------------------------------
        */
        Route::resource('users', UserController::class)
            ->except(['show']);

        /*
        |--------------------------------------------------
        | Role Management
        |--------------------------------------------------
        */
        Route::resource('roles', RoleController::class)
            ->except(['show']);

        /*
        |--------------------------------------------------
        | Services Management
        |--------------------------------------------------
        */
        Route::resource('services', ServiceController::class)
            ->except(['show']);

        /*
        |--------------------------------------------------
        | Slider Management
        |--------------------------------------------------
        */
        Route::resource('sliders', SliderController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::get('/loans', [LoanReviewController::class, 'index'])->name('loans.index');
        Route::get('/loans/{loan}', [LoanReviewController::class, 'show'])->name('loans.show');
        Route::post('/loans/{loan}/approve', [LoanReviewController::class, 'approve'])->name('loans.approve');
        Route::post('/loans/{loan}/reject', [LoanReviewController::class, 'reject'])->name('loans.reject');
        Route::post('/loans/{loan}/status', [LoanReviewController::class, 'setStatus'])->name('loans.status');
        Route::post('/admin/loans/{loan}/disburse', [LoanController::class, 'disburse'])->name('loans.disburse');

        Route::post('/loans/{loan}/workflow', [LoanReviewController::class, 'updateWorkflow'])
            ->name('loans.workflow.update');
    });



Route::middleware(['auth'])->group(function () {
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

require __DIR__ . '/auth.php';
