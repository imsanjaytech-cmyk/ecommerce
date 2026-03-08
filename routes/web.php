<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\NewsLetterController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\CustomersController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\SettingsController;

// ── Debug (remove after confirming DB is set up) ───────────────────────────────
Route::get('/debug-db', function () {
    return response()->json([
        'orders_table'      => Schema::hasTable('orders'),
        'order_items_table' => Schema::hasTable('order_items'),
        'migrations'        => DB::table('migrations')->pluck('migration'),
        'tables'            => DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'"),
    ]);
});

// ── Public ─────────────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search',  [SearchController::class, 'index'])->name('search');
Route::get('/contact', [EnquiryController::class, 'create'])->name('contact');
Route::post('/enquiry', [EnquiryController::class, 'store'])->name('enquiry.store');
Route::post('/newsletter/subscribe', [NewsLetterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/track-order',  [OrderController::class, 'track'])->name('track.order');
Route::post('/track-order', [OrderController::class, 'trackResult'])->name('track.order.result');

// ── Auth ───────────────────────────────────────────────────────────────────────
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register']);
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');
Route::get('/auth/google',          [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// ── Products ───────────────────────────────────────────────────────────────────
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/',       [ProductController::class, 'index'])->name('index');
    Route::get('/search', [ProductController::class, 'search'])->name('search');
    Route::get('/{slug}', [ProductController::class, 'show'])->name('show');
});

// ── Cart ───────────────────────────────────────────────────────────────────────
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/',        [CartController::class, 'index'])->name('index');
    Route::post('/add',    [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    Route::get('/clear',   [CartController::class, 'clear'])->name('clear');
});

// ── Checkout & Orders (public) ─────────────────────────────────────────────────
Route::get('/checkout',     [CheckoutController::class, 'index'])->name('checkout');
Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('place.order');
Route::post('/orders',      [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/{order}/confirmation', [OrderController::class, 'confirmation'])->name('orders.confirmation');
Route::post('/payment-success', [OrderController::class, 'paymentSuccess'])->name('payment.success');
Route::view('/success', 'pages.success')->name('success');
Route::view('/failed',  'pages.failed')->name('failed');

// ── Auth-protected (customer) ──────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/wishlist',         [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/profile',                [AccountController::class, 'profile'])->name('profile');
        Route::post('/profile',               [AccountController::class, 'updateProfile'])->name('profile.update');
        Route::get('/orders',                 [AccountController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}',         [AccountController::class, 'orderDetail'])->name('order.detail');
        Route::post('/orders/{order}/cancel', [AccountController::class, 'cancel'])->name('order.cancel');
    });
});

// ── Admin ──────────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'adminDashboard'])->name('dashboard');

    // Products
    Route::get('/products',                      [ProductsController::class, 'index'])->name('products');
    Route::get('/products/list',                 [ProductsController::class, 'list'])->name('products.list');
    Route::get('/products/stats',                [ProductsController::class, 'stats'])->name('products.stats');
    Route::post('/products',                     [ProductsController::class, 'store'])->name('products.store');
    Route::get('/products/{product}',            [ProductsController::class, 'show'])->name('products.show');
    Route::put('/products/{product}',            [ProductsController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}',         [ProductsController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/bulk-delete',         [ProductsController::class, 'bulkDelete'])->name('products.bulk-delete');
    Route::patch('/products/{product}/featured', [ProductsController::class, 'toggleFeatured'])->name('products.featured');
    Route::delete('/product-images/{image}',     [ProductsController::class, 'deleteImage'])->name('products.image.delete');

    // Orders
    Route::get('/orders',                  [OrdersController::class, 'index'])->name('orders');
    Route::get('/orders/{order}',          [OrdersController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrdersController::class, 'updateStatus'])->name('orders.status');
    Route::delete('/orders/{order}',       [OrdersController::class, 'destroy'])->name('orders.destroy');

    // Categories
    Route::get('/categories',                     [CategoriesController::class, 'index'])->name('categories');
    Route::get('/categories/list',                [CategoriesController::class, 'list'])->name('categories.list');
    Route::post('/categories',                    [CategoriesController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}',          [CategoriesController::class, 'show'])->name('categories.show');
    Route::put('/categories/{category}',          [CategoriesController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}',       [CategoriesController::class, 'destroy'])->name('categories.destroy');
    Route::patch('/categories/{category}/toggle', [CategoriesController::class, 'toggleActive'])->name('categories.toggle');

    // Customers
    Route::get('/customers',           [CustomersController::class, 'index'])->name('customers');
    Route::delete('/customers/{user}', [CustomersController::class, 'destroy'])->name('customers.destroy');

    // Reports & Settings
    Route::get('/reports',   [ReportsController::class,  'index'])->name('reports');
    Route::get('/settings',  [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
});
