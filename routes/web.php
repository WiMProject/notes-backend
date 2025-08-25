<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api', function () {
    return response()->json([
        'message' => 'Notes API - Backend Developer Test',
        'author' => 'Wildan Miladji',
        'documentation' => url('/api/documentation'),
        'endpoints' => [
            'POST /api/register' => 'Register user baru',
            'POST /api/login' => 'Login user',
            'GET /api/me' => 'Get user profile',
            'POST /api/logout' => 'Logout user',
            'GET /api/notes' => 'Get all notes',
            'POST /api/notes' => 'Create note',
            'GET /api/notes/{id}' => 'Get specific note',
            'PUT /api/notes/{id}' => 'Update note',
            'DELETE /api/notes/{id}' => 'Delete note',
        ]
    ]);
});

Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Endpoint tidak ditemukan',
        'errors' => [
            'route' => ['Silakan akses /api/documentation untuk melihat dokumentasi API']
        ]
    ], 404);
});
