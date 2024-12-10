<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\be\DashboardController;
use App\Http\Controllers\be\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');


    // Data User

    Route::group(['prefix' => 'data-user'], function () {
        Route::get('/', [UserController::class, 'index'])->name('data-user.index');
        Route::post('/create', [UserController::class, 'create'])->name('data-user.create');
        Route::post('/store', [UserController::class, 'store'])->name('data-user.store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('data-user.edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('data-user.update');
        Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('data-user.destroy');
    });

    // Route::prefix('data-user')->middleware('can:admin-only')->group(function () {
    //     Route::get('/', [UserController::class, 'index'])->name('data-user.index');
    //     // Route::get('/create', [UserController::class, 'create'])->name('data-user.create');
    //     // Route::post('/store', [UserController::class, 'store'])->name('data-user.store');
    //     // Route::get('/edit/{id}', [UserController::class, 'edit'])->name('data-user.edit');
    //     // Route::put('/update/{id}', [UserController::class, 'update'])->name('data-user.update');
    //     // Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('data-user.destroy');
    // });
});
