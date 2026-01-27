<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LedgerController;
use App\Http\Controllers\Api\CustomersController;
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

// Example protected route (optional):
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Your dashboard API route:
Route::post('dashboard', [AuthController::class, 'dashboard'])->name('api.extra.dashboard');
Route::post('/store-ledger', [LedgerController::class,'storee']);
Route::apiResource('customers', CustomersController::class);

// Add other API routes below as needed
