<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AiController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\GitHubController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\AttachmentController;

Route::get('/auth/github',          [GitHubController::class, 'redirect'])->name('github.redirect');
Route::get('/auth/github/callback', [GitHubController::class, 'callback'])->name('github.callback');
Route::get('auth/google',           [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('auth/google/callback',  [GoogleController::class, 'callback'])->name('google.callback');
Route::get('/invite/{token}',       [InvitationController::class, 'verify'])->name('invitations.verify');

Route::middleware(['auth', 'setlocale'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('projects', ProjectController::class);

    Route::post('projects/{project}/tasks',              [TaskController::class, 'store'])  ->name('tasks.store');
    Route::patch('projects/{project}/tasks/{task}',      [TaskController::class, 'update']) ->name('tasks.update');
    Route::delete('projects/{project}/tasks/{task}',     [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::patch('projects/{project}/tasks/{task}/move', [TaskController::class, 'move'])   ->name('tasks.move');

    // Pieces jointes
    Route::post('tasks/{task}/attachments',                              [AttachmentController::class, 'store'])   ->name('attachments.store');
    Route::delete('tasks/{task}/attachments/{attachment}',               [AttachmentController::class, 'destroy']) ->name('attachments.destroy');
    Route::get('tasks/{task}/attachments/{attachment}/download',         [AttachmentController::class, 'download'])->name('attachments.download');

    Route::post('tasks/{task}/comments',             [CommentController::class, 'store'])  ->name('comments.store');
    Route::delete('tasks/{task}/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::get('/team', [TeamController::class, 'index'])->name('team.index');

    Route::get('/settings',            [SettingsController::class, 'index'])         ->name('settings.index');
    Route::put('/settings/profile',    [SettingsController::class, 'updateProfile']) ->name('settings.profile');
    Route::put('/settings/password',   [SettingsController::class, 'updatePassword'])->name('settings.password');
    Route::post('/settings/theme',     [SettingsController::class, 'updateTheme'])   ->name('settings.theme');
    Route::post('/settings/language',  [SettingsController::class, 'updateLanguage'])->name('settings.language');
    Route::delete('/settings/account', [SettingsController::class, 'deleteAccount']) ->name('settings.account.delete');

    Route::post('projects/{project}/generate-tasks', [AiController::class, 'generate'])->name('projects.generate-tasks');

    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead'])   ->name('notifications.markRead');
    Route::post('/notifications/read-all',  [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

    Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.store');

    Route::get('projects/{project}/chat',              [ChatController::class, 'index'])  ->name('chat.index');
    Route::post('projects/{project}/chat',             [ChatController::class, 'store'])  ->name('chat.store');
    Route::delete('projects/{project}/chat/{message}', [ChatController::class, 'destroy'])->name('chat.destroy');

    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    Route::get('/calendar',                     [CalendarController::class, 'index'])     ->name('calendar.index');
    Route::get('/calendar/events',              [CalendarController::class, 'events'])    ->name('calendar.events');
    Route::patch('/calendar/tasks/{task}/date', [CalendarController::class, 'updateDate'])->name('calendar.update-date');

});

require __DIR__.'/auth.php';