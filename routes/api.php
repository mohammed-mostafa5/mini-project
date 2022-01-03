<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('test', function () {
    return 'ok';
});

//////////////////////////////// Start Auth //////////////////////////////////
Route::post('user/register', [ AuthController::class,'register']);
Route::post('user/login', [ AuthController::class,'login']);
//////////////////////////////// End Auth //////////////////////////////////

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('customer')->middleware('auth:sanctum')->group(function () {
    Route::post('logout', [ AuthController::class,'logout']);

    Route::get('transactions', [ CustomerController::class,'customerTransactions']);
});

Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {

    Route::get('categories', [ AdminController::class, 'getCategories']);
    Route::post('categories/create', [ AdminController::class, 'createCategory']);
    Route::post('subcategories/create', [ AdminController::class, 'createSubcategory']);
    Route::post('transactions/create', [ AdminController::class, 'createTransaction']);
    Route::get('transactions/view/{transaction}', [ AdminController::class, 'viewTransaction']);
    Route::post('payments/create', [ AdminController::class, 'createPayment']);
    Route::get('payments/view/{payment}', [ AdminController::class, 'viewPayment']);
    Route::get('report/generate', [ AdminController::class, 'generateReport']);
});
