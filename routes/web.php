<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BusinessSettingController;
use App\Http\Controllers\BusinessLocationController;

// Route::middleware(['auth'])->group(function () {
Route::get('/', function () {
    return view('auth.login');
});
Auth::routes();
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
Route::resource('/role', RoleController::class);
Route::resource('/user', UserController::class);
Route::resource('/post', PostController::class);
Route::post('/post/{id}/toggle-published', [PostController::class, 'togglePublished'])->name('post.toggle-published');
Route::resource('/category', CategoryController::class);
Route::resource('/unit', UnitController::class);
Route::resource('/brand', BrandController::class);
Route::resource('/product', ProductController::class);
Route::resource('/business-location', BusinessLocationController::class);
Route::get('/settings/business', [BusinessSettingController::class, 'edit'])->name('business-setting.edit');
Route::put('/settings/business', [BusinessSettingController::class, 'update'])->name('business-setting.update');
Route::resource('author', AuthorController::class);
// });

// Route::prefix('admin')->group(function () {
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
