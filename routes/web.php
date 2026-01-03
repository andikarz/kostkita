<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KostController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\UnifiedLoginController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// Beranda
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);

// Pencarian & detail kost (publik)
Route::get('/cari', [SearchController::class, 'index'])->name('kost.search');
Route::get('/kost/{kost}', [KostController::class, 'show'])->name('kost.show.id');

// Route auth bawaan Laravel (register, password reset, dll)
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| LOGIN / LOGOUT GABUNGAN (USER & ADMIN)
|--------------------------------------------------------------------------
*/

// Form login gabungan
Route::get('/login', [UnifiedLoginController::class, 'showLoginForm'])
    ->middleware('guest')
    ->name('login');

// Proses login
Route::post('/login', [UnifiedLoginController::class, 'login'])
    ->middleware('guest');

// Logout (dipakai user & admin, controller cek guard sendiri)
Route::post('/logout', [UnifiedLoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Kalau ada yang akses /admin/login, arahkan ke /login
Route::get('/admin/login', function () {
    return redirect()->route('login');
})->name('admin.login');


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (GUARD: admin)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:admin')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard admin
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        // CRUD kost versi admin -> name: admin.kost.*
        Route::resource('kost', KostController::class)
            ->except(['show']);

        // Hapus foto tambahan kost
        Route::delete('/kost/photo/{photo}', [KostController::class, 'destroyPhoto'])
            ->name('kost.photo.destroy');
    });


/*
|--------------------------------------------------------------------------
| USER / PEMILIK ROUTES (GUARD: web)
|--------------------------------------------------------------------------
|
| Di sini user biasa & pemilik kost sama-sama pakai guard "web".
| Bedanya nanti bisa kamu bedakan via kolom role di tabel users.
|--------------------------------------------------------------------------
*/

// PROFILE
Route::middleware('auth')
    ->prefix('profile')
    ->name('profile.')
    ->group(function () {

        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::get('/riwayat', [ProfileController::class, 'riwayat'])->name('riwayat');
        Route::get('/syarat', [ProfileController::class, 'syarat'])->name('syarat');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
        Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');
        Route::get('/create', [ProfileController::class, 'create'])->name('create');
    });

// BOOKING (User biasa)
Route::middleware('auth')
    ->prefix('booking')
    ->name('booking.')
    ->group(function () {

        Route::get('/create/{kost}', [BookingController::class, 'create'])
            ->name('create');

        Route::post('/{kost}', [BookingController::class, 'store'])
            ->name('store');

        Route::get('/{booking}/print', [BookingController::class, 'print'])
            ->name('print');

        Route::get('/{booking}/payment', [BookingController::class, 'payment'])
            ->name('payment');

        Route::post('/{booking}/payment', [BookingController::class, 'paymentStore'])
            ->name('payment.store');
    });

// Rating (setelah booking)
Route::middleware('auth')->group(function () {
    Route::post('/booking/{booking}/rating', [RatingController::class, 'store'])
        ->name('booking.rating.store');
});

// Paymemt Midtrans
Route::middleware('auth')->group(function () {
    Route::get('/payment/checkout/{booking}', [App\Http\Controllers\PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/payment/finish', [App\Http\Controllers\PaymentController::class, 'finish'])->name('payment.finish');
});

Route::post('/payment/notification', [App\Http\Controllers\PaymentController::class, 'notification'])->name('payment.notification');


/*
|--------------------------------------------------------------------------
| PEMILIK KOST - MANAJEMEN KOST (GUARD: web)
|--------------------------------------------------------------------------
|
| Resource kost di luar prefix admin:
| - GET   /kost           -> kost.index
| - GET   /kost/create    -> kost.create
| - POST  /kost           -> kost.store
| - GET   /kost/{kost}/edit -> kost.edit
| - PUT   /kost/{kost}    -> kost.update
| - DELETE /kost/{kost}   -> kost.destroy
|
| Ini yang dipakai di sidebar pemilik:
|   route('kost.index'), route('kost.create'), dst.
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::resource('kost', KostController::class)->except(['show']);
    Route::patch('/kost/{kost}/stock', [KostController::class, 'updateStock'])->name('kost.updateStock');
});


/*
|--------------------------------------------------------------------------
| STATIC PAGES
|--------------------------------------------------------------------------
*/

Route::get('/kebijakan-privasi', function () {
    return view('pages.kebijakan privasi');
})->name('terms');

Route::get('/tentang', function () {
    return view('pages.tentang');
})->name('about');
