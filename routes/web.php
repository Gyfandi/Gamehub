<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminGameController;
use App\Http\Controllers\Admin\AdminPublisherController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminDiscountController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminTransactionController;

// ==========================================
// PUBLIC ROUTES
// ==========================================
Route::get('/', [StoreController::class, 'landing'])->name('landing');
Route::get('/catalog', [StoreController::class, 'catalog'])->name('catalog');
Route::get('/game/{id}', [StoreController::class, 'detail'])->name('detail');

// ==========================================
// AUTH ROUTES
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginView'])->name('auth.login.view');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('/register', [AuthController::class, 'registerView'])->name('auth.register.view');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
});

Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// ==========================================
// BUYER ROUTES (authenticated)
// ==========================================
Route::middleware(['auth'])->group(function () {
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove/{game_id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout
Route::get('/checkout', [CheckoutController::class, 'checkoutView'])->name('checkout.view');
Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');

    // Library
    Route::get('/library', [LibraryController::class, 'index'])->name('library');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Reviews
    Route::post('/review', [ReviewController::class, 'store'])->name('review.store');

    // Wishlist
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
});

// ==========================================
// ADMIN ROUTES
// ==========================================
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Games CRUD
    Route::get('/games', [AdminGameController::class, 'index'])->name('admin.games');
    Route::post('/games', [AdminGameController::class, 'store'])->name('admin.games.store');
    Route::post('/games/update/{id}', [AdminGameController::class, 'update'])->name('admin.games.update');
    Route::post('/games/delete/{id}', [AdminGameController::class, 'destroy'])->name('admin.games.delete');
    Route::delete('/games/images/delete/{imageId}', [AdminGameController::class, 'destroyImage'])->name('admin.games.images.delete');
    Route::post('/games/images/move/{imageId}', [AdminGameController::class, 'moveImage'])->name('admin.games.images.move');

    // Categories CRUD
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('admin.categories');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('admin.categories.store');
    Route::post('/categories/update/{id}', [AdminCategoryController::class, 'update'])->name('admin.categories.update');
    Route::post('/categories/delete/{id}', [AdminCategoryController::class, 'destroy'])->name('admin.categories.delete');

    // Publishers CRUD
    Route::get('/publishers', [AdminPublisherController::class, 'index'])->name('admin.publishers');
    Route::post('/publishers', [AdminPublisherController::class, 'store'])->name('admin.publishers.store');
    Route::post('/publishers/update/{id}', [AdminPublisherController::class, 'update'])->name('admin.publishers.update');
    Route::post('/publishers/delete/{id}', [AdminPublisherController::class, 'destroy'])->name('admin.publishers.delete');

    // Discounts CRUD
    Route::get('/discounts', [AdminDiscountController::class, 'index'])->name('admin.discounts');
    Route::post('/discounts', [AdminDiscountController::class, 'store'])->name('admin.discounts.store');
    Route::post('/discounts/update/{id}', [AdminDiscountController::class, 'update'])->name('admin.discounts.update');
    Route::post('/discounts/delete/{id}', [AdminDiscountController::class, 'destroy'])->name('admin.discounts.delete');

    // Users Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::post('/users/update/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');

    // Transactions Log
    Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('admin.transactions');
    Route::get('/transactions/{id}', [AdminTransactionController::class, 'show'])->name('admin.transactions.detail');
});
