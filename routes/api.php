<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Authentication method
Route::post("/auth/login", [UserController::class, 'login']);

Route::get("/products", [ProductController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    // methods for product
Route::controller(ProductController::class)->group(function () {
        Route::post("/products", "store");
        Route::post("/products/{product}", "update");
        Route::delete("/products/{product}", "destroy");
    });
});

// methods for cart
Route::controller(CartController::class)->group(function () {
    Route::post("/cart", "store");
    Route::put("/cart/{cart}", "update");
    Route::delete("/cart/{cart}", "destroy");
    Route::get("/cart", "index");
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
