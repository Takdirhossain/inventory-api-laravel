<?php
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductsController;
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
Route::prefix('customers')->group(function(){
    Route::post('/',[CustomersController::class, 'addCustomer']);
    Route::get('/',[CustomersController::class, 'getCustomer']);
    Route::post('/{id}',[CustomersController::class, 'updateCustomer']);
    Route::delete('/{id}',[CustomersController::class, 'deleteCustomer']);
});

Route::prefix('products')->group(function(){
Route::post('/', [ProductsController::class, 'addProduct']);
Route::get('/', [ProductsController::class, 'getProducts']);
Route::put('/states', [ProductsController::class,'getStates']);
Route::post('/{id}', [ProductsController::class, 'updateProduct']);
Route::delete('/{id}',[ProductsController::class, 'deleteProduct']);
});
