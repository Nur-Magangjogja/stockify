<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Manager;
use App\Http\Controllers\Staff;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return match (auth()->user()->role) {
        'Admin'          => redirect()->route('admin.dashboard'),
        'Manajer Gudang' => redirect()->route('manager.dashboard'),
        'Staff Gudang'   => redirect()->route('staff.dashboard'),
        default          => abort(403),
    };
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

//Admin Routes
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:Admin'])
    ->group(function () {
    
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Settings Route
    Route::get('/settings', [Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [Admin\SettingController::class, 'update'])->name('settings.update');

    Route::resource('products', Admin\ProductController::class);
    Route::resource('categories', Admin\CategoryController::class)->except(['show']);
    Route::resource('suppliers', Admin\SupplierController::class)->except(['show']);
    
    Route::resource('users', Admin\UserController::class)->except(['show']);
    Route::post('users/{id}/toggle-status', [Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');

    Route::prefix('stocks')->name('stocks.')->group(function (){
        Route::get('/',             [Admin\StockController::class, 'index'])->name('index');
        Route::get('/in/create',    [Admin\StockController::class, 'createIn'])->name('in.create');
        Route::post('/in',          [Admin\StockController::class, 'storeIn'])->name('in.store');
        Route::get('/out/create',   [Admin\StockController::class, 'createOut'])->name('out.create');
        Route::post('/out',         [Admin\StockController::class, 'storeOut'])->name('out.store');
        Route::post('/{id}/confirm', [Admin\StockController::class, 'confirm'])->name('confirm');
        Route::post('/{id}/cancel',  [Admin\StockController::class, 'cancel'])->name('cancel');
        Route::get('/opname', [Admin\StockController::class, 'opnameIndex'])->name('opname.index');
        Route::post('/opname', [Admin\StockController::class, 'opnameStore'])->name('opname.store');
    });

    //Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/',             [Admin\ReportController::class, 'index'])->name('index');
        Route::get('/stock',        [Admin\ReportController::class, 'stockReport'])->name('stock');
        Route::get('/transactions', [Admin\ReportController::class, 'transactionReport'])->name('transactions');
        Route::get('/activities',   [Admin\ReportController::class, 'activityReport'])->name('activities');
    });
});

//Manager
Route::prefix('manager')
    ->name('manager.')
    ->middleware(['auth', 'role:Manajer Gudang'])
    ->group(function () {

    Route::get('/dashboard', [Manager\DashboardController::class, 'index'])->name('dashboard');

    //Products (read only)
    Route::get('/products', [Admin\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}', [Admin\ProductController::class, 'show'])->name('products.show');

    //Suppliers (read only)
    Route::get('/suppliers', [Admin\SupplierController::class, 'index'])->name('suppliers.index');

    //Stocks
    Route::prefix('stocks')->name('stocks.')->group(function () {
        Route::get('/',             [Manager\StockController::class, 'index'])->name('index');
        Route::get('/in/create',    [Manager\StockController::class, 'createIn'])->name('in.create');
        Route::post('/in',          [Manager\StockController::class, 'storeIn'])->name('in.store');
        Route::get('/out/create',   [Manager\StockController::class, 'createOut'])->name('out.create');
        Route::post('/out',         [Manager\StockController::class, 'storeOut'])->name('out.store');
        Route::post('/{id}/confirm', [Manager\StockController::class, 'confirm'])->name('confirm');
        Route::get('/opname', [Manager\StockController::class, 'opnameIndex'])->name('opname.index');
        Route::post('/opname', [Manager\StockController::class, 'opnameStore'])->name('opname.store');

    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/',             [Manager\ReportController::class, 'index'])->name('index');
        Route::get('/stock',        [Manager\ReportController::class, 'stockReport'])->name('stock');
        Route::get('/transactions', [Manager\ReportController::class, 'transactionReport'])->name('transactions');
    });
});

//Staff
Route::prefix('staff')
    ->name('staff.')
    ->middleware(['auth', 'role:Staff Gudang'])
    ->group(function () {

    Route::get('/dashboard', [Staff\DashboardController::class, 'index'])->name('dashboard');

    Route::post('/stocks/{id}/confirm-in',  [Staff\StockController::class, 'confirmIn'])->name('stocks.confirm-in');
    Route::post('/stocks/{id}/confirm-out', [Staff\StockController::class, 'confirmOut'])->name('stocks.confirm-out');
});

