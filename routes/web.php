<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchasingController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\ChartOfAccountController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Resource Routes
Route::resource('products', ProductController::class);
Route::resource('suppliers', SupplierController::class);

// Purchasing Routes
Route::prefix('purchase')->name('purchase.')->group(function () {
    // Goods Receipt - MUST be before /{purchase}
    Route::get('/goods-receipt', [PurchasingController::class, 'goodsReceiptIndex'])->name('goods_receipt.index');
    Route::get('/goods-receipt/create', [PurchasingController::class, 'goodsReceiptCreate'])->name('goods_receipt.create');
    Route::post('/goods-receipt', [PurchasingController::class, 'goodsReceiptStore'])->name('goods_receipt.store');
    Route::get('/goods-receipt/{goodsReceipt}', [PurchasingController::class, 'goodsReceiptShow'])->name('goods_receipt.show');
    
    // Invoice - MUST be before /{purchase}
    Route::get('/invoice', [PurchasingController::class, 'invoiceIndex'])->name('invoice.index');
    Route::get('/invoice/create', [PurchasingController::class, 'invoiceCreate'])->name('invoice.create');
    Route::post('/invoice', [PurchasingController::class, 'invoiceStore'])->name('invoice.store');
    Route::get('/invoice/{invoice}', [PurchasingController::class, 'invoiceShow'])->name('invoice.show');
    Route::post('/invoice/{invoice}/pay', [PurchasingController::class, 'invoicePay'])->name('invoice.pay');
    
    // Purchase - MUST be last
    Route::get('/', [PurchasingController::class, 'index'])->name('index');
    Route::get('/create', [PurchasingController::class, 'create'])->name('create');
    Route::post('/', [PurchasingController::class, 'store'])->name('store');
    Route::get('/{purchase}', [PurchasingController::class, 'show'])->name('show');
    Route::delete('/{purchase}', [PurchasingController::class, 'destroy'])->name('destroy');
});

// Sales Routes
Route::prefix('sales')->name('sales.')->group(function () {
    Route::get('/', [SalesController::class, 'index'])->name('index');
    Route::get('/create', [SalesController::class, 'create'])->name('create');
    Route::post('/', [SalesController::class, 'store'])->name('store');
    Route::get('/{sale}', [SalesController::class, 'show'])->name('show');
    Route::delete('/{sale}', [SalesController::class, 'destroy'])->name('destroy');
});

// Stock Opname Routes
Route::prefix('stock-opname')->name('stock_opname.')->group(function () {
    Route::get('/', [SalesController::class, 'stockOpnameIndex'])->name('index');
    Route::post('/', [SalesController::class, 'stockOpnameStore'])->name('store');
});

// Accounting Routes
Route::prefix('accounting')->name('accounting.')->group(function () {
    Route::get('/', [AccountingController::class, 'index'])->name('index');
    Route::get('/{journalEntry}', [AccountingController::class, 'show'])->name('show');
});

Route::resource('chart-of-accounts', ChartOfAccountController::class)->names('chart-of-accounts');
Route::patch('chart-of-accounts/{chartOfAccount}/deactivate', [ChartOfAccountController::class, 'deactivate'])->name('chart-of-accounts.deactivate');
