<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api([
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token sudah kedaluwarsa',
                'errors' => [
                    'token' => ['Silakan login ulang untuk mendapatkan token baru']
                ]
            ], 401);
        });
        
        $exceptions->render(function (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid',
                'errors' => [
                    'token' => ['Format token tidak sesuai atau token rusak']
                ]
            ], 401);
        });
        
        $exceptions->render(function (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token diperlukan',
                'errors' => [
                    'authorization' => ['Header Authorization dengan Bearer token diperlukan']
                ]
            ], 401);
        });
        
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak terautentikasi',
                'errors' => [
                    'authentication' => ['Anda harus login terlebih dahulu']
                ]
            ], 401);
        });
        
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Endpoint tidak ditemukan',
                'errors' => [
                    'route' => ['URL yang Anda akses tidak tersedia']
                ]
            ], 404);
        });
        
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Method tidak diizinkan',
                'errors' => [
                    'method' => ['Method HTTP yang digunakan tidak diizinkan untuk endpoint ini']
                ]
            ], 405);
        });
    })->create();
