<?php

use App\Http\Controllers\Api\CustomerController;
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

Route::group(['prefix' => 'v1'], function () {
    Route::post('customer-store', [CustomerController::class, 'store'])
        ->name('api.customer.store');

    Route::post('customer-update', [CustomerController::class, 'update'])
        ->name('api.customer.update');

    Route::post('customer-delete', [CustomerController::class, 'delete'])
        ->name('api.customer.delete');
});
