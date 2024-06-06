<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DailyCashController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\SubCategoryController;
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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::controller(ProductController::class)->group(function (){
      Route::get('/products', 'index')->name('product.index');
    });

    Route::controller(CategoryController::class)->group(function(){
      Route::get('/categories', 'index')->name('category.index');
      Route::post('/create-category', 'store')->name('category.create');
      Route::put('/update-category/{category}', 'update')->name('category.update');
      Route::delete('/delete-category/{category}', 'destroy')->name('category.delete');
    });

    Route::controller(SubCategoryController::class)->group(function(){
      Route::get('/sub-categories', 'index')->name('subCategory.index');
      Route::post('/create-sub-category', 'store')->name('subCategory.create');
      Route::put('/update-sub-category/{subCategory}', 'update')->name('subCategory.update');
      Route::delete('/delete-sub-category/{subCategory}', 'destroy')->name('subCategory.delete');
    });

    Route::controller(DailyCashController::class)->group(function(){
      Route::get('/daily-cash', 'index')->name('dailyCash.index');
      Route::post('/create-daily-cash', 'store')->name('dailyCash.create');
      Route::put('/update-daily-cash/{dailyCash}', 'update')->name('dailyCash.update');
      Route::put('/status-daily-cash/{dailyCash}', 'changeStatus')->name('dailyCash.status');
      Route::delete('/delete-daily-cash/{dailyCash}', 'destroy')->name('subCategory.delete');
    });

    // Route::resource('subCategory', SubCategoryController::class);
    Route::resource('provider', ProviderController::class);
    Route::resource('product', ProductController::class);
  });

require __DIR__.'/auth.php';
