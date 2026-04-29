<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\AiController;

Route::middleware(['auth'])->group(function () {

    // Dashboard = liste des projets
    Route::get('/', [ProjectController::class, 'index'])
         ->name('dashboard');

    // CRUD Projets
    Route::resource('projects', ProjectController::class);

    // Tâches (imbriquées dans un projet)
    Route::prefix('projects/{project}/tasks')->name('tasks.')->group(function () {
        Route::post('/',           [TaskController::class, 'store'])  ->name('store');
        Route::patch('/{task}',    [TaskController::class, 'update']) ->name('update');
        Route::delete('/{task}',   [TaskController::class, 'destroy'])->name('destroy');
        Route::patch('/{task}/move', [TaskController::class, 'move']) ->name('move');
    });

    // Commentaires (imbriqués dans une tâche)
    Route::prefix('tasks/{task}/comments')->name('comments.')->group(function () {
        Route::post('/',         [CommentController::class, 'store'])  ->name('store');
        Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('destroy');
    });

    // Team
    Route::get('/team', [TeamController::class, 'index'])
         ->name('team.index');

    // Settings
    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings.index');
    // IA - Génération de tâches
Route::post('projects/{project}/generate-tasks', 
    [AiController::class, 'generate'])
    ->name('projects.generate-tasks');

});

require __DIR__.'/auth.php';