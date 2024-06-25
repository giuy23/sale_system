<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CreditSaleController;
use App\Http\Controllers\DailyCashController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\UserController;
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

  Route::controller(UserController::class)->group(function () {
    Route::put('/user-state/{user}', 'changeState')->name('user.changeState');
  });

  Route::controller(ClientController::class)->group(function () {
    Route::put('/client-state/{client}', 'changeState')->name('client.changeState');
    Route::get('/client-search-to-sell', 'clientsToSell')->name('client.search');
  });

  Route::controller(ProductController::class)->group(function () {
    // Route::get('/products', 'index')->name('product.index');
    Route::put('/product-state/{product}', 'changeState')->name('product.changeState');
    Route::get('/products-search-to-sell', 'productsToSell')->name('product.search');
  });

  Route::controller(SaleController::class)->group(function () {
    Route::get('/sale-cancel/{sale}', 'cancelSale')->name('sale.cancelSale');
    Route::get('/sale-detail/{sale}', 'getSaleDetail')->name('sale.detail');
    Route::post('/sale-confirm-cancel', 'confirmCancelSale')->name('sale.confirmCancelSale');

  });

  Route::controller(CreditSaleController::class)->group(function (){
    Route::get('/debt-client-detail', 'getDebtsFromClient')->name('creditSale.getClientDebts');
    Route::post('/pay-one-debt/{creditSale}', 'payOneDebt')->name('creditSale.payOneDebt');
    Route::post('/pay-one-debt', 'payAllDebts')->name('creditSale.payAllDebt');
  });

  // Route::controller(CategoryController::class)->group(function () {
  //   Route::get('/categories', 'index')->name('category.index');
  //   Route::post('/create-category', 'store')->name('category.create');
  //   Route::put('/update-category/{category}', 'update')->name('category.update');
  //   Route::delete('/delete-category/{category}', 'destroy')->name('category.delete');
  // });

  // Route::controller(SubCategoryController::class)->group(function () {
  //   Route::get('/sub-categories', 'index')->name('subCategory.index');
  //   Route::post('/create-sub-category', 'store')->name('subCategory.create');
  //   Route::put('/update-sub-category/{subCategory}', 'update')->name('subCategory.update');
  //   Route::delete('/delete-sub-category/{subCategory}', 'destroy')->name('subCategory.delete');
  // });

  Route::controller(DailyCashController::class)->group(function () {
    Route::get('/daily-cash', 'index')->name('dailyCash.index');
    Route::post('/create-daily-cash', 'store')->name('dailyCash.create');
    Route::put('/update-daily-cash/{dailyCash}', 'update')->name('dailyCash.update');
    Route::put('/status-daily-cash/{dailyCash}', 'changeStatus')->name('dailyCash.status');
    Route::delete('/delete-daily-cash/{dailyCash}', 'destroy')->name('subCategory.delete');
  });

  Route::controller(ShopController::class)->group(function () {
    Route::post('/create-sale', 'createSale')->name('shop.createSale');
  });


  // Route::resource('subCategory', SubCategoryController::class);
  Route::resource('user', UserController::class);
  Route::resource('client', ClientController::class);
  Route::resource('subCategory', SubCategoryController::class);
  Route::resource('category', CategoryController::class);
  Route::resource('provider', ProviderController::class);
  Route::resource('product', ProductController::class);
  Route::resource('expense', ExpenseController::class);
  Route::resource('shop', ShopController::class);
  Route::resource('sale', SaleController::class);
  Route::resource('saleCredit', CreditSaleController::class);
});

require __DIR__ . '/auth.php';
