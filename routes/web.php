<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\BorrowingController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});


Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('tools', ToolController::class)->except(['show']);

    Route::get('/borrowings', [BorrowingController::class, 'index'])
        ->name('borrowings.index');

    Route::get('/borrowings/create', [BorrowingController::class, 'create'])
        ->name('borrowings.create');

    Route::post('/borrowings', [BorrowingController::class, 'store'])
        ->name('borrowings.store');

    Route::put('/borrowings/return-direct', [BorrowingController::class, 'returnDirect'])
        ->name('borrowings.return-direct');

    Route::get('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnForm'])
        ->name('borrowings.return-form');

    Route::put('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnTool'])
        ->name('borrowings.return');
});