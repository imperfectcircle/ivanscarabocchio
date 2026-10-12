<?php

use App\Http\Controllers\Admin\SecuritySettingsController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::inertia('/', 'dashboard')->name('dashboard');

    Route::get('sicurezza', [SecuritySettingsController::class, 'show'])
        ->middleware('password.confirm')
        ->name('security');
});
