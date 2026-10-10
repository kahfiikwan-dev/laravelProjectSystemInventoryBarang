<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke dashboard atau login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Autentikasi Manual (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Routes yang memerlukan Login (Admin & Staff)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Barang Masuk (Admin & Staff)
    Route::get('/stock-in', [StockInController::class, 'index'])->name('stock-in.index');
    Route::get('/stock-in/create', [StockInController::class, 'create'])->name('stock-in.create');
    Route::post('/stock-in', [StockInController::class, 'store'])->name('stock-in.store');
    Route::delete('/stock-in/{stockIn}', [StockInController::class, 'destroy'])->name('stock-in.destroy');

    // Barang Keluar (Admin & Staff)
    Route::get('/stock-out', [StockOutController::class, 'index'])->name('stock-out.index');
    Route::get('/stock-out/create', [StockOutController::class, 'create'])->name('stock-out.create');
    Route::post('/stock-out', [StockOutController::class, 'store'])->name('stock-out.store');
    Route::delete('/stock-out/{stockOut}', [StockOutController::class, 'destroy'])->name('stock-out.destroy');

    // Produk (Lihat Barang - Admin & Staff)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');

    // Khusus Admin (Kelola Kategori, Kelola Barang Create/Edit/Delete, Kelola User)
    Route::middleware('role:admin')->group(function () {
        // Kelola Barang (CRUD Admin)
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Kelola Kategori
        Route::resource('categories', CategoryController::class);

        // Kelola User
        Route::resource('users', UserController::class);
    });

    // Detail Produk (Diletakkan setelah /products/create agar tidak bentrok wildcard)
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
});
