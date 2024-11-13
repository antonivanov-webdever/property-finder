<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\PointController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::group([
    'prefix' => 'admin',
    'middleware' => [
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified'
    ]
], function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::group(['prefix' => 'points'], function () {
        Route::get('/', [PointController::class, 'index'])->name('points.index');

        Route::get('/create', [PointController::class, 'create'])->name('points.create');

        Route::get('/{point}/edit', [PointController::class, 'edit'])->name('points.edit');

        Route::post('/create', [PointController::class, 'store'])->name('points.store');

        Route::post('/save', [PointController::class, 'save'])->name('points.save');

        Route::post('/{point}', [PointController::class, 'update'])->name('points.update');

        Route::delete('/{point}', [PointController::class, 'destroy'])->name('points.destroy');
    });

    Route::group(['prefix' => 'filters'], function () {
        Route::get('/', [FilterController::class, 'index'])->name('filters.index');

//        Route::get('/create', [PointController::class, 'create'])->name('points.create');
//
//        Route::get('/{point}/edit', [PointController::class, 'edit'])->name('points.edit');
//
//        Route::post('/create', [PointController::class, 'store'])->name('points.store');
//
//        Route::put('/{point}', [PointController::class, 'update'])->name('points.update');
//
//        Route::delete('/{point}', [PointController::class, 'destroy'])->name('points.destroy');

        Route::get('/getAll', [FilterController::class, 'getAll'])->name('filters.getAll');
    });
});
