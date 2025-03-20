<?php
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');




Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Routes protégées
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return response()->json(['message' => 'Bienvenue sur le dashboard Admin']);
    })->name('admin.dashboard');

    Route::get('/client/dashboard', function () {
        return response()->json(['message' => 'Bienvenue sur le dashboard Client']);
    })->name('client.dashboard');
});
