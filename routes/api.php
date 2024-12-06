<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskAnalyticsController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AdvancedAnalyticsController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); 
Route::middleware(['web', 'auth'])->group(function () {
    // Analytics routes
    Route::get('/analytics/monthly', [AnalyticsController::class, 'getMonthlyData'])
        ->middleware(['auth']);
    Route::prefix('analytics')->group(function () {
        Route::get('/advanced', [AdvancedAnalyticsController::class, 'getAdvancedMetrics']);
        Route::get('/productivity', [AdvancedAnalyticsController::class, 'getProductivityScore']);
        Route::get('/patterns', [AdvancedAnalyticsController::class, 'getWeeklyPatterns']);
        Route::get('/trends', [AdvancedAnalyticsController::class, 'getTrends']);
    });
    
    // Task routes
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
}); 