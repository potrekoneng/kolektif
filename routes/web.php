<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\Auth\PhotoController;
use App\Http\Controllers\Auth\PreviewLastPhoto;
use App\Http\Controllers\Auth\KolektifController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    // $user = auth()->user();
    // return "Selamat datang, " . $user;
    return Inertia::render('Dashboard');
    // })->middleware(['isAdmin'])->name('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('viewlastdata', [KolektifController::class, 'lastTakePicture'])->name('kolektif.takepicture');

Route::get('lastimagecrop', [PhotoController::class, 'lastCropPicture'])->name('kolektif.lastcrop');
Route::get('viewlastimage', function () {
    return view('welcome'); 
});

Route::get('/update/{id}/data', function () {
    return Inertia::render('Welcome');
});

    Route::get('/latest-image', [PreviewLastPhoto::class, 'show']);
    Route::get('/latest-preview', [PreviewLastPhoto::class, 'preview']);
    Route::get('/latest-image-meta', [PreviewLastPhoto::class, 'cekMetaImage']);
    
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
