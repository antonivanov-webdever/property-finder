<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PointController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home', []);
});

Route::get('/getPointsOMJson', [PointController::class, 'getPointsOMJson'])->name('points.getPointsOMJson');

Route::get('/getAll', [CategoryController::class, 'getAll'])->name('categories.getAll');

Route::group([
    'prefix' => 'admin',
    'middleware' => [
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified'
    ]
], function () {
    Route::get('/dashboard', [AdminController::class, 'preview'])->name('dashboard');

    Route::post('/csv', [AdminController::class, 'uploadPointsListCsv'])->name('csv');

    Route::post('/points/filter', [PointController::class, 'filter'])->name('points.filter');

    Route::group(['prefix' => 'points'], function () {
        Route::get('/', [PointController::class, 'index'])->name('points.index');

        Route::get('/create', [PointController::class, 'create'])->name('points.create');

        Route::get('/{point}/edit', [PointController::class, 'edit'])->name('points.edit');

        Route::post('/create', [PointController::class, 'store'])->name('points.store');

        Route::post('/save', [PointController::class, 'save'])->name('points.save');

        Route::post('/{point}', [PointController::class, 'update'])->name('points.update');

        Route::delete('/{point}', [PointController::class, 'destroy'])->name('points.destroy');

        Route::get('/search', [PointController::class, 'search'])->name('points.search');
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoryController::class, 'index'])->name('categories.index');

        Route::get('/create', [CategoryController::class, 'create'])->name('categories.create');

        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');

        Route::post('/create', [CategoryController::class, 'store'])->name('categories.store');

        Route::post('/{category}', [CategoryController::class, 'update'])->name('categories.update');

        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });
});
