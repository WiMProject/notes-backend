<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

// Authentication routes with rate limiting
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected routes
Route::middleware(['auth:api', 'throttle:60,1'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/update-profile', [AuthController::class, 'updateProfile']);
    Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);
    
    // Notes CRUD
    Route::apiResource('notes', NoteController::class);
});