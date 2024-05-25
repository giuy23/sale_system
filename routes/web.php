<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
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
    });
  });

require __DIR__.'/auth.php';
