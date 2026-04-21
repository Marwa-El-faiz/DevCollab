<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;


Route::middleware(['auth'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])
         ->name('dashboard');

    Route::get('/projects', function () {
        return view('projects.index');
    })->name('projects.index');

    Route::get('/team', function () {
        return view('team.index');
    })->name('team.index');

    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings.index');

});


require __DIR__.'/auth.php';