<?php
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Middleware\AuthenticateWithToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});
Route::prefix('company')->group(function () {
    Route::post('/add', [CompanyController::class, 'addCompany']);
});


Route::middleware([AuthenticateWithToken::class])->prefix('expense')->group(function () {
    Route::post('/', [ExpenseController::class, 'addExpense']);
    Route::get('/', [ExpenseController::class, 'getExpense']);
    Route::get('/{id}', [ExpenseController::class, 'singleExpense']);
    Route::post('/{id}', [ExpenseController::class, 'updateExpense']);
    Route::delete('/{id}', [ExpenseController::class, 'deleteExpense']);
    Route::get('/states', [ExpenseController::class, 'getStates']);
});
Route::middleware([AuthenticateWithToken::class])->prefix('customers')->group(function () {
    Route::post('/', [CustomersController::class, 'addCustomer']);
    Route::put('/', [CustomersController::class, 'getCustomerWithSum']);
    Route::put('/recentcustomers', [CustomersController::class, 'lastCustomers']);
    Route::post('/{id}', [CustomersController::class, 'updateCustomer']);
    Route::delete('/{id}', [CustomersController::class, 'deleteCustomer']);
});

Route::middleware([AuthenticateWithToken::class])->prefix('products')->group(function () {
    Route::post('/', [ProductsController::class, 'addProduct']);
    Route::put('/', [ProductsController::class, 'getProducts']);
    Route::put('/{id}', [ProductsController::class, 'updateProduct']);
    Route::delete('/{id}', [ProductsController::class, 'deleteProduct']);
    Route::get('/laststock', [ProductsController::class, 'getLastStock']);
    Route::put('/stock/states', [ProductsController::class, 'getStates']);
    Route::get('/stock/update', [ProductsController::class, 'getUpdatedStock']);
});

Route::middleware([AuthenticateWithToken::class])->prefix('sales')->group(function () {
    Route::post('/', [SalesController::class, 'newSales']);
    Route::put('/', [SalesController::class, 'getSalesList']);
    Route::put('/collectionList', [SalesController::class, 'getCollectionList']);
    Route::get('/lastsale', [SalesController::class, 'getLastsale']);
    Route::put('/compare', [SalesController::class, 'getCompares']);
    Route::get('/stock', [SalesController::class, 'getStock']);
    Route::get('/recentSales', [SalesController::class, 'getRecent']);
    Route::put('/profittoday', [SalesController::class, 'getProfittoday']);
    Route::get('/todaysales', [SalesController::class, 'getTodaySales']);
    Route::get('/mothsales', [SalesController::class, 'monthsales']);
    Route::get('/cash', [SalesController::class, 'cash']);
    Route::put('/{id}', [SalesController::class, 'editSales']);
    Route::delete('/{id}', [SalesController::class, 'deleteSales']);
});
Route::middleware([AuthenticateWithToken::class])->prefix('customer')->group(function () {
    Route::put('/states', [SalesController::class, 'customerStates']);
});

