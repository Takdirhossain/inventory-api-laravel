<?php

use App\Http\Controllers\ExpenseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

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
