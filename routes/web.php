<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\WaiterController;
use Illuminate\Support\Facades\Route;

// Homepage = categories
Route::get('/', [CategoryController::class, 'index'])->name('categories.index');

// Category products
Route::get('/categories/{category}/products', [CategoryController::class, 'show'])->name('categories.products');

// Cart
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
Route::post('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/edit/{item}', [CartController::class, 'edit'])->name('cart.edit');
Route::post('/cart/edit/{item}', [CartController::class, 'updateAdditions'])->name('cart.updateAdditions');
Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('/order/confirmation/{order}', [CartController::class, 'confirmation'])->name('order.confirmation');

// Kitchen dashboard (restricted to kitchen role in controller)
Route::middleware('auth')->group(function () {
    Route::get('/kitchen', [KitchenController::class, 'index'])->name('kitchen.index');
    Route::post('/kitchen/orders/{order}/status', [KitchenController::class, 'updateStatus'])->name('kitchen.updateStatus');
    Route::get('/kitchen/orders', [KitchenController::class, 'getOrders'])->name('kitchen.getOrders');
});

// Waiter dashboard (restricted to waiter role in controller)
Route::middleware('auth')->group(function () {
    Route::get('/waiter', [WaiterController::class, 'index'])->name('waiter.index');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

