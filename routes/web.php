<?php
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\DistanceController;

Route::get('/', function () {
    return redirect()->route('users.index');
});
Route::get('/distance-calculator', function () {
    return view('distance.calculator');
});
Route::resource('users', UserController::class);
Route::get('users-export', [UserController::class, 'export'])->name('users.export');
Route::resource('audio', AudioController::class);
Route::get('/audio', [AudioController::class, 'index'])->name('audio.index');
Route::get('/distance-calculator', [DistanceController::class, 'index']);
Route::post('/calculate-distance', [DistanceController::class, 'calculate']);
