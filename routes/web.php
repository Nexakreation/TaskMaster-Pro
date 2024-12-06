<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\TaskAnalyticsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/api/notes', [NoteController::class, 'index']);
    Route::post('/api/notes', [NoteController::class, 'store']);
    Route::post('/api/notes/reorder', [NoteController::class, 'reorder']);
    Route::delete('/api/notes/{note}', [NoteController::class, 'destroy']);
    Route::patch('/api/notes/{note}', [NoteController::class, 'update']);

    Route::get('/api/tasks', [TaskController::class, 'index']);
    Route::post('/api/tasks', [TaskController::class, 'store']);
    Route::put('/api/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/api/tasks/{id}', [TaskController::class, 'destroy']);

    Route::get('/tasks/today', function () {
        return view('tasks.today');
    })->middleware(['auth'])->name('tasks.today');

    Route::get('/tasks/upcoming', function () {
        return view('tasks.upcoming');
    })->middleware(['auth'])->name('tasks.upcoming');

    Route::get('/notes', function () {
        return view('notes.index');
    })->middleware(['auth'])->name('notes.index');

    Route::get('/tasks', function () {
        return view('tasks.index');
    })->middleware(['auth'])->name('tasks.index');

    Route::get('/calendar', function () {
        return view('calendar.index');
    })->middleware(['auth'])->name('calendar.index');

    Route::get('/analytics', [TaskAnalyticsController::class, 'index'])->middleware(['auth'])->name('analytics');
    Route::get('/api/analytics/monthly', [AnalyticsController::class, 'getMonthlyData'])
        ->name('analytics.monthly')
        ->middleware(['auth']);
    Route::get('/api/analytics/python', [TaskAnalyticsController::class, 'getPythonAnalysis'])
        ->middleware(['auth'])
        ->name('analytics.python');
});

require __DIR__.'/auth.php';
