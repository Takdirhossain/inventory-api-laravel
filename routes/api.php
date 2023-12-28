<?php
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SalesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('expense')->group(function () {
    Route::post('/', [ExpenseController::class, 'addExpense'])->name('expense');
    Route::get('/', [ExpenseController::class, 'getExpense']);
    Route::get('/{id}', [ExpenseController::class, 'singleExpense']);
    Route::post('/{id}', [ExpenseController::class, 'updateExpense']);
    Route::delete('/{id}', [ExpenseController::class, 'deleteExpense']);
});
Route::prefix('customers')->group(function () {
    Route::post('/', [CustomersController::class, 'addCustomer']);
    Route::get('/', [CustomersController::class, 'getCustomerWithSum']);
    Route::get('/', [CustomersController::class, 'getCustomerWithSum']);
    Route::get('/recentcustomers', [CustomersController::class, 'lastCustomers']);
    Route::post('/{id}', [CustomersController::class, 'updateCustomer']);
    Route::delete('/{id}', [CustomersController::class, 'deleteCustomer']);
});

Route::prefix('products')->group(function () {
    Route::post('/', [ProductsController::class, 'addProduct']);
    Route::put('/', [ProductsController::class, 'getProducts']);
    Route::put('/{id}', [ProductsController::class, 'updateProduct']);
    Route::delete('/{id}', [ProductsController::class, 'deleteProduct']);
    Route::get('/laststock', [ProductsController::class, 'getLastStock']);
    Route::put('/stock/states', [ProductsController::class, 'getStates']);
});

Route::prefix('sales')->group(function () {
    Route::post('/', [SalesController::class, 'newSales']);
    Route::put('/', [SalesController::class, 'getSalesList']);
    Route::put('/collectionList', [SalesController::class, 'getCollectionList']);
    Route::get('/lastsale', [SalesController::class, 'getLastsale']);
    Route::put('/compare', [SalesController::class, 'getCompares']);
    Route::get('/stock', [SalesController::class, 'getStock']);
    Route::get('/recentSales', [SalesController::class, 'getRecent']);
    Route::put('/profittoday', [SalesController::class, 'getProfittoday']);
    Route::get('/todaySales', [SalesController::class, 'getTodaySales']);
    Route::get('/mothsales', [SalesController::class, 'monthsales']);
    Route::put('/{id}', [SalesController::class, 'editSales']);
    Route::delete('/{id}', [SalesController::class, 'deleteSales']);


});
