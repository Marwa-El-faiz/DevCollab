<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AiController;

Route::middleware(['auth', 'setlocale'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])
         ->name('dashboard');

    
    Route::resource('projects', ProjectController::class);

    Route::post('projects/{project}/tasks', [TaskController::class, 'store'])
         ->name('tasks.store');
    Route::patch('projects/{project}/tasks/{task}', [TaskController::class, 'update'])
         ->name('tasks.update');
    Route::delete('projects/{project}/tasks/{task}', [TaskController::class, 'destroy'])
         ->name('tasks.destroy');
    Route::patch('projects/{project}/tasks/{task}/move', [TaskController::class, 'move'])
         ->name('tasks.move');

    Route::post('tasks/{task}/comments', [CommentController::class, 'store'])
         ->name('comments.store');
    Route::delete('tasks/{task}/comments/{comment}', [CommentController::class, 'destroy'])
         ->name('comments.destroy');

    Route::get('/team', [TeamController::class, 'index'])
         ->name('team.index');

    Route::get('/settings',              [SettingsController::class, 'index'])          ->name('settings.index');
    Route::put('/settings/profile',      [SettingsController::class, 'updateProfile'])  ->name('settings.profile');
    Route::put('/settings/password',     [SettingsController::class, 'updatePassword']) ->name('settings.password');
    Route::post('/settings/theme',       [SettingsController::class, 'updateTheme'])    ->name('settings.theme');
    Route::post('/settings/language',    [SettingsController::class, 'updateLanguage']) ->name('settings.language');

    Route::post('projects/{project}/generate-tasks', [AiController::class, 'generate'])
         ->name('projects.generate-tasks');
});

require __DIR__.'/auth.php';