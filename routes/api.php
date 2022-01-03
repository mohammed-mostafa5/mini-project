<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::post('logout', [ AuthController::class,'logout']);
});

Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {

    Route::post('category/create', [ AdminController::class, 'createCategory']);
});
