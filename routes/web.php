<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

// Route::middleware(['auth'])->group(function () {
//     Route::get('/', function () {
//         return view('auth.login');
//     });
//     Auth::routes();
//     Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
//     Route::resource('/role', RoleController::class);
//     Route::resource('/user', UserController::class);
//     Route::resource('/post', PostController::class);
//     Route::post('/post/{id}/toggle-published', [PostController::class, 'togglePublished'])->name('post.toggle-published');
// });
Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    });
    Auth::routes();
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
    Route::resource('/role', RoleController::class);
    Route::resource('/user', UserController::class);
    Route::resource('/post', PostController::class);
    Route::post('/post/{id}/toggle-published', [PostController::class, 'togglePublished'])->name('post.toggle-published');
});
