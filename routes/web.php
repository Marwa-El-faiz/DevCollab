<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AiController;

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])
         ->name('dashboard');

    // Projets
    Route::resource('projects', ProjectController::class);

    // Tâches
    Route::post('projects/{project}/tasks', [TaskController::class, 'store'])
         ->name('tasks.store');
    Route::put('projects/{project}/tasks/{task}', [TaskController::class, 'update'])
         ->name('tasks.update');
    Route::delete('projects/{project}/tasks/{task}', [TaskController::class, 'destroy'])
         ->name('tasks.destroy');
    Route::patch('projects/{project}/tasks/{task}/move', [TaskController::class, 'move'])
         ->name('tasks.move');

    // Commentaires
    Route::post('tasks/{task}/comments', [CommentController::class, 'store'])
         ->name('comments.store');
    Route::delete('tasks/{task}/comments/{comment}', [CommentController::class, 'destroy'])
         ->name('comments.destroy');

    // Team
    Route::get('/team', [TeamController::class, 'index'])
         ->name('team.index');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])
         ->name('settings.index');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])
         ->name('settings.profile');
    Route::put('/settings/password', [SettingsController::class, 'updatePassword'])
         ->name('settings.password');

    // IA
    Route::post('projects/{project}/generate-tasks', [AiController::class, 'generate'])
         ->name('projects.generate-tasks');
});

require __DIR__.'/auth.php';