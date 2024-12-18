<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\be\HeroController;
use App\Http\Controllers\be\TeamController;
use App\Http\Controllers\be\UserController;
use App\Http\Controllers\be\ProfileController;
use App\Http\Controllers\fe\LandingController;
use App\Http\Controllers\be\DashboardController;
use App\Http\Controllers\be\UserSettingAccountController;

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




Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::get('/contact', [LandingController::class, 'contactIndex'])->name('landing.contactIndex');
Route::get('/about', [LandingController::class, 'aboutIndex'])->name('landing.aboutIndex');
Route::get('/service', [LandingController::class, 'serviceIndex'])->name('landing.serviceIndex');
Route::get('/testimonial', [LandingController::class, 'testimonialIndex'])->name('landing.testimonialIndex');
Route::get('/blog', [LandingController::class, 'blogIndex'])->name('landing.blogIndex');

Auth::routes();

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Data User
    Route::group(['prefix' => 'be/user'], function () {
        Route::get('/', [UserController::class, 'index'])->name('be/user.index');
        Route::post('/create', [UserController::class, 'create'])->name('be/user.create');
        Route::post('/store', [UserController::class, 'store'])->name('be/user.store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('be/user.edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('be/user.update');
        Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('be/user.destroy');
    });

    // Profile
    Route::group(['prefix' => 'be/profile'], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('be/profile.index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('be/profile.edit');
        Route::post('/store', [ProfileController::class, 'store'])->name('be/profile.store');
        Route::put('/update', [ProfileController::class, 'update'])->name('be/profile.update');
    });

    // Account Setting
    Route::group(['prefix' => 'be/account/setting'], function () {
        Route::get('/', [UserSettingAccountController::class, 'index'])->name('be/account/setting.index');
        Route::get('/edit', [UserSettingAccountController::class, 'edit'])->name('be/account/setting.edit');
        Route::put('/update', [UserSettingAccountController::class, 'update'])->name('be/account/setting.update');
    });

    // Hero
    Route::group(['prefix' => 'be/hero'], function () {
        Route::get('/', [HeroController::class, 'index'])->name('be/hero.index');
        Route::get('/preview', [HeroController::class, 'preview'])->name('be/hero.preview');
        Route::get('/create', [HeroController::class, 'create'])->name('be/hero.create');
        Route::post('/store', [HeroController::class, 'store'])->name('be/hero.store');
        Route::get('/edit/{id}', [HeroController::class, 'edit'])->name('be/hero.edit');
        Route::put('/update/{id}', [HeroController::class, 'update'])->name('be/hero.update');
        Route::delete('/destroy/{id}', [HeroController::class, 'destroy'])->name('be/hero.destroy');
        Route::post('toggle-status/{id}', [HeroController::class, 'toggleStatus'])->name('be/hero.toggle-status');
    });

    // Teams
    Route::group(['prefix' => 'be/teams'], function () {
        Route::get('/', [TeamController::class, 'index'])->name('be/teams.index');
        Route::get('/create', [TeamController::class, 'create'])->name('be/teams.create');
        Route::post('/store', [TeamController::class, 'store'])->name('be/teams.store');
        Route::get('/edit/{id}', [TeamController::class, 'edit'])->name('be/teams.edit');
        Route::put('/update/{id}', [TeamController::class, 'update'])->name('be/teams.update');
        Route::delete('/destroy/{id}', [TeamController::class, 'destroy'])->name('be/teams.destroy');
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
