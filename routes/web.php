<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SpecialistController as AdminSpecialistController;
use App\Http\Controllers\Admin\SpecialtyController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SpecialistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Website
|--------------------------------------------------------------------------
*/

Route::get('/', HomeController::class)->name('home');

Route::get('/about', AboutController::class)->name('about');

// Bound on the slug so profile URLs are readable and stable to link to.
Route::get('/specialists/{specialist:slug}', [SpecialistController::class, 'show'])
    ->name('specialists.show');

Route::post('/appointments', [AppointmentController::class, 'store'])
    ->name('appointments.store');

/*
|--------------------------------------------------------------------------
| Admin Panel
|--------------------------------------------------------------------------
|
| Staff-only area for triaging booking requests and maintaining the
| specialists and specialties shown on the public website.
|
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'create'])->name('login');
        Route::post('login', [AuthController::class, 'store'])->name('login.store');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'destroy'])->name('logout');

        Route::get('/', DashboardController::class)->name('dashboard');

        // Bulk routes are declared before the resources so the literal "bulk"
        // segment is never mistaken for a record binding.
        Route::post('appointments/bulk', [AdminAppointmentController::class, 'bulk'])->name('appointments.bulk');
        Route::post('specialists/bulk', [AdminSpecialistController::class, 'bulk'])->name('specialists.bulk');
        Route::post('specialties/bulk', [SpecialtyController::class, 'bulk'])->name('specialties.bulk');

        Route::patch('appointments/{appointment}/status', [AdminAppointmentController::class, 'updateStatus'])
            ->name('appointments.status');

        Route::resource('appointments', AdminAppointmentController::class)
            ->only(['index', 'show', 'update', 'destroy']);

        Route::resource('specialists', AdminSpecialistController::class)->except(['show']);

        Route::resource('specialties', SpecialtyController::class)->except(['show']);
    });
});
