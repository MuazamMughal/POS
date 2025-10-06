<?php

use App\Http\Controllers\DashboardHome;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaleController;
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
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardHome::class, 'index'])->name('dashboard');
    Route::get('/detailings', [DashboardHome::class, 'detailings'])->name('detailings');
    
    // Separate routes for each type
    Route::post('/detailings/category', [DashboardHome::class, 'storeCategory'])->name('store.category');
    Route::post('/detailings/brand', [DashboardHome::class, 'storeBrand'])->name('store.brand');
    Route::post('/detailings/unit', [DashboardHome::class, 'storeUnit'])->name('store.unit');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
//routes for products
Route::middleware('auth')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');  
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/update/{product}', [ProductController::class, 'update'])->name('products.update');
    
});

Route::middleware('auth')->group(function () {
    Route::get('/pos', [SaleController::class, 'index'])->name('pos.index');
    Route::post('/pos', [SaleController::class, 'store'])->name('pos.store');
});

require __DIR__.'/auth.php';
