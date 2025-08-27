<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\KolektifController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\DataController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\PhotoController;
use App\Http\Controllers\Auth\PreviewLastPhoto;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login8811', [AuthenticatedSessionController::class, 'create'])
        ->name('login8811');
    Route::post('login8811', [AuthenticatedSessionController::class, 'store']);

    Route::get('login/{slug?}', [AuthenticatedSessionController::class, 'createLogin'])
        ->name('login');
    Route::post('login/{slug}', [AuthenticatedSessionController::class, 'storeLogin']);

    Route::get('/update/{id}/data', [AuthenticatedSessionController::class, 'create'])
        ->name('customer.login');

    Route::post('customerlogin', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // Route::get('roles', function () {
    //     $data = auth()->user();
    //     return $data->username;
    // })->name('customer.role');
});

Route::middleware(['isAdmin'])->group(function () {
    // Route::get('kolektif', function () {
    //     // $user = auth()->user();
    //     // return "Selamat datang, " . $user;
    //     return Inertia::render('Dashboard');
    // })->middleware(['auth', 'verified'])->name('dashboard');
    Route::get('roles', [KolektifController::class, 'roles'])
        ->name('roles');
    Route::post('roles', [KolektifController::class, 'storeRoles']);
    Route::get('kolektif', [KolektifController::class, 'agent'])
        ->name('customer.agent');
    Route::post('kolektif', [KolektifController::class, 'import']);
    Route::get('kolektif/{id}/data', [KolektifController::class, 'isiAgent'])->name('kolektif.data');
    Route::get('kolektif/{id}/{tipe}/spesific', [KolektifController::class, 'isiAgentSpesific'])->name('kolektif.spesific');
    Route::get('kolektif/{id}/queue', [KolektifController::class, 'queueAgentLocked'])->name('kolektif.queue');
    Route::get('kolektif/{id}/foto', [KolektifController::class, 'isiAgentLocked'])->name('kolektif.foto');
    Route::post('kolektif/{user_id}/unlock', [KolektifController::class, 'unlockUser'])->name('unlock.user');

    Route::get('files/{id}/data', [DataController::class, 'getDataAgency'])->name('view.data');
    Route::get('export/{idAgency}/{tanggal}/data', [DataController::class, 'exportData'])->name('export.data');
    Route::get('export/{idAgency}/{tipe}', [DataController::class, 'exportSpesificData'])->name('export.spesific.data');

    // Route::post('prosesfoto/{id}', [PhotoController::class, 'prosesFoto'])->name('kolektif.prosesfoto'); //  proses foto
    Route::post('prosesfoto/{id}', [PhotoController::class, 'prosesCorel'])->name('kolektif.prosesfoto'); // proses corel

    Route::get('switchuser/{id}', [KolektifController::class, 'redirectLoginUser']);

    Route::post('agency', [KolektifController::class, 'storeAgency'])->name('agency.store');
    Route::post('user/{agency_id}', [KolektifController::class, 'storeUser'])->name('user.store');
});
