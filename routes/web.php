<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\TaskAnalyticsController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', function () {
    return view('welcome');
});

// Route to home dashboard (after successful login)
Route::get('/home', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('home');

// Standard dashboard route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notes API routes
    Route::get('taskmaster-pro/public/api/notes', [NoteController::class, 'index']);
    Route::post('taskmaster-pro/public/api/notes', [NoteController::class, 'store']);
    Route::post('taskmaster-pro/public/api/notes/reorder', [NoteController::class, 'reorder']);
    Route::delete('taskmaster-pro/public/api/notes/{note}', [NoteController::class, 'destroy']);
    Route::patch('taskmaster-pro/public/api/notes/{note}', [NoteController::class, 'update']);

    // Tasks API routes
    Route::get('taskmaster-pro/public/api/tasks', [TaskController::class, 'index']);
    Route::post('taskmaster-pro/public/api/tasks', [TaskController::class, 'store']);
    Route::put('taskmaster-pro/public/api/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('taskmaster-pro/public/api/tasks/{id}', [TaskController::class, 'destroy']);

    // Task views
    Route::get('/tasks/today', function () {
        return view('tasks.today');
    })->name('tasks.today');

    Route::get('/tasks/upcoming', function () {
        return view('tasks.upcoming');
    })->name('tasks.upcoming');

    // Notes views
    Route::get('/notes', function () {
        return view('notes.index');
    })->name('notes.index');

    Route::get('/tasks', function () {
        return view('tasks.index');
    })->name('tasks.index');

    // Calendar view
    Route::get('/calendar', function () {
        return view('calendar.index');
    })->name('calendar.index');

    // Analytics routes
    Route::get('/analytics', [TaskAnalyticsController::class, 'index'])->name('analytics');
    Route::get('/api/analytics/monthly', [AnalyticsController::class, 'getMonthlyData'])->name('analytics.monthly');
    Route::get('/api/analytics/python', [TaskAnalyticsController::class, 'getPythonAnalysis'])->name('analytics.python');
});

require __DIR__ . '/auth.php'; // Ensure this is included to load authentication routes
