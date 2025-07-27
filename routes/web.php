<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RequestController;
use App\Http\Controllers\Admin\DailyStockRecordController;
use App\Http\Controllers\CabinCrew\CabinCrewController;
use App\Http\Controllers\Admin\StockTransactionController;
use App\Http\Controllers\Admin\OpeningStockController;
use App\Http\Controllers\PasswordResetController;

use App\Http\Controllers\CabinCrew\RequestController as CabinCrewRequestController;

// Login routes
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.attempt');

Route::middleware(['auth'])->group(function () {
    Route::get('/reset-password', [PasswordResetController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.reset.save');
});

// Cabin Crew routes (role: cabin_crew)
Route::group(['middleware' => ['auth', 'check_role:cabin_crew']], function() {
    Route::get('/cabin_crew', [CabinCrewController::class, 'index'])->name('cabin.crew');

    // Routes for Cabin Crew to create a request
    Route::get('/cabin_crew/requests/create', [CabinCrewRequestController::class, 'create'])->name('requests.create');
    
    Route::post('/cabin_crew/requests', [CabinCrewRequestController::class, 'store'])->name('requests.store');
    
    // ✅ New route for other size request
    Route::get('/cabin_crew/requests/other-size', [CabinCrewRequestController::class, 'createOtherSize'])->name('requests.createOtherSize');

    Route::post('/cabin_crew/requests/other-size', [CabinCrewRequestController::class, 'storeOtherSize'])->name('requests.storeOtherSize');
});

// Admin routes
Route::group(['middleware' => ['auth', 'check_role:admin']], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Menampilkan daftar Opening Stock
    Route::get('/admin/opening-stock/list', [OpeningStockController::class, 'index'])->name('admin.opening-stock.index');

    // Opening Stock Input
    Route::get('/admin/opening-stock', [OpeningStockController::class, 'create'])->name('admin.opening-stock.create');
    Route::post('/admin/opening-stock', [OpeningStockController::class, 'store'])->name('admin.opening-stock.store');
    Route::delete('/admin/opening-stock/bulk-delete', [OpeningStockController::class, 'bulkDelete'])->name('admin.opening-stock.bulk-delete');


        // Stock In (Transaksi Masuk)
    Route::get('/admin/stock-in/create', [StockTransactionController::class, 'create'])->name('admin.stock-in.create');
    Route::post('/admin/stock-in', [StockTransactionController::class, 'store'])->name('admin.stock-in.store');
// Stock Out from Approved Request
Route::post('/admin/stock-out/from-request/{requestId}', [StockTransactionController::class, 'storeOutFromRequest'])->name('admin.stock-out.from-request');

Route::get('/admin/stock-transactions', [StockTransactionController::class, 'index'])->name('admin.transactions.index');



    // Users (Cabin Crew)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/admin/users', [UserController::class, 'store'])->name('users.store');

    Route::get('/admin/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');      // ✅ Tambahan
    Route::put('/admin/users/{id}', [UserController::class, 'update'])->name('users.update');       // ✅ Tambahan
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::put('/admin/users/{id}/update-items', [UserController::class, 'updateItems'])->name('users.updateItems');
    Route::post('/admin/users/{id}/add-items', [UserController::class, 'addItems'])->name('users.addItems');
    
     // ✅ Route baru untuk personal profile admin (melihat & edit data diri sendiri)
    Route::get('/admin/profile', [UserController::class, 'editSelf'])->name('admin.personal-profile');
    Route::put('/admin/profile', [UserController::class, 'updateSelf'])->name('admin.personal-profile.update');
    
//SIZES
    Route::get('/admin/items/{id}/sizes', [\App\Http\Controllers\Admin\OpeningStockController::class, 'getSizes']);

    //Route::get('/admin/items/{item}/sizes', [UserController::class, 'getSizes'])->name('admin.items.sizes');
    Route::get('/users/active', [UserController::class, 'activeUsers'])->name('admin.users.active');

    // CRUD Items
    // Only define what you need
    Route::get('/items', function () {
    return redirect('/items/current-stock');
});
Route::get('/items', function () {
    return redirect('/items/current-stock');
});

Route::get('/items/current-stock', [ItemController::class, 'index'])->name('admin.items.index');
Route::get('/items/create', [ItemController::class, 'create'])->name('admin.items.create');
Route::post('/items', [ItemController::class, 'store'])->name('admin.items.store');
Route::get('/items/{item}', [ItemController::class, 'show'])->name('admin.items.show');
Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('admin.items.edit');
Route::put('/items/{item}', [ItemController::class, 'update'])->name('admin.items.update');
Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('admin.items.destroy');


    // Re-Order Page: Menampilkan item yang perlu di-reorder
    Route::get('/admin/items/reorder', [ItemController::class, 'reorder'])->name('admin.items.reorder');


    // ✅ Tambahan: Detail item (per ukuran & stok)
    Route::get('/admin/items/{item}/detail', [ItemController::class, 'showDetail'])->name('admin.items.detail');
    
    Route::put('/admin/items/{item}', [ItemController::class, 'update'])->name('admin.items.update');




    // Laporan Stok Harian
    Route::get('/admin/daily-stock-records', [DailyStockRecordController::class, 'index'])->name('daily_stock_records.index');
    Route::put('/admin/daily-stock-records/{id}', [DailyStockRecordController::class, 'update'])->name('daily_stock_records.update'); // ✅ Tambahkan ini
    Route::get('/admin/daily-stock-records/create', [DailyStockRecordController::class, 'create'])->name('daily_stock_records.create');
    Route::post('/admin/daily-stock-records', [DailyStockRecordController::class, 'store'])->name('daily_stock_records.store');

    // Requests
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('requests', [RequestController::class, 'index'])->name('requests.index');

        // ✅ Tambahan: Approval request
        Route::patch('requests/{id}/approve', [RequestController::class, 'approve'])->name('requests.approve');
        Route::patch('requests/{id}/reject', [RequestController::class, 'reject'])->name('requests.reject');
        Route::patch('requests/{id}/waiting-return', [RequestController::class, 'waitingReturn'])->name('requests.waitingReturn');

        
    // ✅ Tambahan: Hapus banyak request sekaligus
    Route::delete('requests/bulk-delete', [RequestController::class, 'bulkDelete'])->name('requests.bulkDelete');
    });

});

// Halaman utama (login diperlukan)
Route::get('/', function () {
    return redirect('/login');
});


// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
