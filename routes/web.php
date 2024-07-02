<?php
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CreditSaleController;
use App\Http\Controllers\DailyCashController;
use App\Http\Controllers\EnterpriseController;
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
    Route::put('/user-image', 'updateImage')->name('user.image');
    Route::put('/user-password', 'updatePassword')->name('user.password');
  });

  Route::controller(ClientController::class)->group(function () {
    Route::put('/client-state/{client}', 'changeState')->name('client.changeState')->middleware('role:1');
    Route::get('/client-search-to-sell', 'clientsToSell')->name('client.search');
  });

  // Rutas de productos accesibles tanto por administradores como vendedores
  Route::get('/products', [ProductController::class, 'index'])->name('product.index')->middleware('role:1,2');
  Route::get('/products-search-to-sell', [ProductController::class, 'productsToSell'])->name('product.search')->middleware('role:1,2');
  Route::get('/products-restock', [ProductController::class, 'getProductsToRestock'])->name('product.restock')->middleware('role:1,2');
  Route::post('/products-best-sell', [ProductController::class, 'getBestSellingProducts'])->name('product.bestSelling')->middleware('role:1,2');
  // Rutas de productos accesibles solo por administradores
  Route::middleware('role:1')->group(function () {
    Route::put('/product-state/{product}', [ProductController::class, 'changeState'])->name('product.changeState');
    Route::resource('products', ProductController::class)->except(['index']);
  });

  Route::controller(SaleController::class)->group(function () {
    Route::get('/sale-cancel/{sale}', 'cancelSale')->name('sale.cancelSale')->middleware('role:1');
    Route::get('/sale-detail/{sale}', 'getSaleDetail')->name('sale.detail')->middleware('role:1,2');
    Route::get('/detail-sale/{sale}', 'getDetailSalePdf')->name('sale.detailSale')->middleware('role:1,2');
    Route::get('/sale-total-amount', 'getAmountTotalSalesToday')->name('sale.totalSales')->middleware('role:1,2');
    Route::post('/sale-confirm-cancel', 'confirmCancelSale')->name('sale.confirmCancelSale')->middleware('role:1');
  });

  Route::controller(CreditSaleController::class)->group(function () {
    Route::get('/debt-client-detail', 'getDebtsFromClient')->name('creditSale.getClientDebts')->middleware('role:1,2');
    Route::post('/pay-one-debt/{creditSale}', 'payOneDebt')->name('creditSale.payOneDebt')->middleware('role:1');
    Route::post('/pay-one-debt', 'payAllDebts')->name('creditSale.payAllDebt')->middleware('role:1');
  });

  Route::controller(DailyCashController::class)->group(function () {
    Route::get('/daily-cash-last-cashes', 'getLastDailyCashes')->name('dailyCash.getLastCashes')->middleware('role:1,2');
    Route::get('/daily-cash-profit', 'getProfit')->name('dailyCash.profit')->middleware('role:1,2');
    Route::put('/status-daily-cash/{dailyCash}', 'changeStatus')->name('dailyCash.status')->middleware('role:1,2');
  });

  Route::controller(ExpenseController::class)->group(function () {
    Route::get('expense-balance', 'getBalance')->name('expense.balance')->middleware('role:1,2');
  });

  Route::controller(ShopController::class)->group(function () {
    Route::post('/create-sale', 'createSale')->name('shop.createSale')->middleware('role:1,2');
  });

  Route::controller(EnterpriseController::class)->group(function () {
    Route::put('enterprise-image', 'updateImage')->name('enterprise.image')->middleware('role:1');
  });

  // Recursos
  Route::resource('user', UserController::class)->middleware('role:1');
  Route::resource('enterprise', EnterpriseController::class)->middleware('role:1');
  Route::resource('client', ClientController::class)->middleware('role:1,2');
  Route::resource('subCategory', SubCategoryController::class)->middleware('role:1,2');
  Route::resource('category', CategoryController::class)->middleware('role:1,2');
  Route::resource('provider', ProviderController::class)->middleware('role:1,2');
  Route::resource('product', ProductController::class)->middleware('role:1,2');
  Route::resource('dailyCash', DailyCashController::class)->middleware('role:1,2');
  Route::resource('expense', ExpenseController::class)->middleware('role:1,2');
  Route::resource('shop', ShopController::class)->middleware('role:1,2');
  Route::resource('sale', SaleController::class)->middleware('role:1,2');
  Route::resource('saleCredit', CreditSaleController::class)->middleware('role:1,2');
});


require __DIR__ . '/auth.php';


// <?php

// use App\Http\Controllers\CategoryController;
// use App\Http\Controllers\ClientController;
// use App\Http\Controllers\CreditSaleController;
// use App\Http\Controllers\DailyCashController;
// use App\Http\Controllers\ExpenseController;
// use App\Http\Controllers\ProductController;
// use App\Http\Controllers\ProfileController;
// use App\Http\Controllers\ProviderController;
// use App\Http\Controllers\ShopController;
// use App\Http\Controllers\SaleController;
// use App\Http\Controllers\SubCategoryController;
// use App\Http\Controllers\UserController;
// use Illuminate\Foundation\Application;
// use Illuminate\Support\Facades\Route;
// use Inertia\Inertia;

// Route::get('/', function () {
//   return Inertia::render('Welcome', [
//     'canLogin' => Route::has('login'),
//     'canRegister' => Route::has('register'),
//     'laravelVersion' => Application::VERSION,
//     'phpVersion' => PHP_VERSION,
//   ]);
// });

// Route::get('/dashboard', function () {
//   return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware(['auth', 'role:1'])->group(function () {
//   Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//   Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//   Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

//   Route::controller(UserController::class)->group(function () {
//     Route::put('/user-state/{user}', 'changeState')->name('user.changeState');
//     Route::put('/user-password', 'updatePassword')->name('user.password');
//   });

//   Route::controller(ClientController::class)->group(function () {
//     Route::put('/client-state/{client}', 'changeState')->name('client.changeState');
//     Route::get('/client-search-to-sell', 'clientsToSell')->name('client.search');
//   });

//   Route::controller(ProductController::class)->group(function () {
//     // Route::get('/products', 'index')->name('product.index');
//     Route::get('/products-search-to-sell', 'productsToSell')->name('product.search');
//     Route::get('/products-restock', 'getProductsToRestock')->name('product.restock');
//     Route::put('/product-state/{product}', 'changeState')->name('product.changeState');
//     Route::post('/products-best-sell', 'getBestSellingProducts')->name('product.bestSelling');
//   });

//   Route::controller(SaleController::class)->group(function () {
//     Route::get('/sale-cancel/{sale}', 'cancelSale')->name('sale.cancelSale');
//     Route::get('/sale-detail/{sale}', 'getSaleDetail')->name('sale.detail');
//     Route::get('/sale-total-amount', 'getAmountTotalSalesToday')->name('sale.totalSales');
//     Route::post('/sale-confirm-cancel', 'confirmCancelSale')->name('sale.confirmCancelSale');

//   });

//   Route::controller(CreditSaleController::class)->group(function () {
//     Route::get('/debt-client-detail', 'getDebtsFromClient')->name('creditSale.getClientDebts');
//     Route::post('/pay-one-debt/{creditSale}', 'payOneDebt')->name('creditSale.payOneDebt');
//     Route::post('/pay-one-debt', 'payAllDebts')->name('creditSale.payAllDebt');
//   });

//   // Route::controller(CategoryController::class)->group(function () {
//   //   Route::get('/categories', 'index')->name('category.index');
//   //   Route::post('/create-category', 'store')->name('category.create');
//   //   Route::put('/update-category/{category}', 'update')->name('category.update');
//   //   Route::delete('/delete-category/{category}', 'destroy')->name('category.delete');
//   // });

//   // Route::controller(SubCategoryController::class)->group(function () {
//   //   Route::get('/sub-categories', 'index')->name('subCategory.index');
//   //   Route::post('/create-sub-category', 'store')->name('subCategory.create');
//   //   Route::put('/update-sub-category/{subCategory}', 'update')->name('subCategory.update');
//   //   Route::delete('/delete-sub-category/{subCategory}', 'destroy')->name('subCategory.delete');
//   // });

//   Route::controller(DailyCashController::class)->group(function () {
//     // Route::get('/daily-cash', 'index')->name('dailyCash.index');
//     // Route::post('/create-daily-cash', 'store')->name('dailyCash.create');
//     // Route::put('/update-daily-cash/{dailyCash}', 'update')->name('dailyCash.update');
//     // Route::delete('/delete-daily-cash/{dailyCash}', 'destroy')->name('subCategory.delete');
//     Route::get('/daily-cash-last-cashes', 'getLastDailyCashes')->name('dailyCash.getLastCashes');
//     Route::get('/daily-cash-profit', 'getProfit')->name('dailyCash.profit');
//     Route::put('/status-daily-cash/{dailyCash}', 'changeStatus')->name('dailyCash.status');
//   });

//   Route::controller(ExpenseController::class)->group(function () {
//     Route::get('expense-balance', 'getBalance')->name('expense.balance');
//   });

//   Route::controller(ShopController::class)->group(function () {
//     Route::post('/create-sale', 'createSale')->name('shop.createSale');
//   });


//   // Route::resource('subCategory', SubCategoryController::class);
//   Route::resource('user', UserController::class);
//   Route::resource('client', ClientController::class);
//   Route::resource('subCategory', SubCategoryController::class);
//   Route::resource('category', CategoryController::class);
//   Route::resource('provider', ProviderController::class);
//   Route::resource('product', ProductController::class);
//   Route::resource('dailyCash', DailyCashController::class);
//   Route::resource('expense', ExpenseController::class);
//   Route::resource('shop', ShopController::class);
//   Route::resource('sale', SaleController::class);
//   Route::resource('saleCredit', CreditSaleController::class);
// });

// require __DIR__ . '/auth.php';
