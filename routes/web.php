<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\DistanceController;

Route::get('/', function () {
    return redirect()->route('users.index');
});

// User routes
Route::resource('users', UserController::class);
Route::get('users-export', [UserController::class, 'export'])->name('users.export');

// Audio routes
Route::get('/audio', [AudioController::class, 'index'])->name('audio.index');
Route::get('/audio/create', [AudioController::class, 'create'])->name('audio.create');
Route::post('/audio', [AudioController::class, 'store'])->name('audio.store');
Route::delete('/audio/{audio}', [AudioController::class, 'destroy'])->name('audio.destroy');

// Distance calculator routes
Route::get('/distance-calculator', [DistanceController::class, 'index']);
Route::post('/calculate-distance', [DistanceController::class, 'calculate']);