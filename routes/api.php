<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuppilersController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/users/{id}', [UserController::class, 'findUserId']);
Route::get('/products/{productid}', [ProductsController::class, 'getProducts']);
Route::get('/categories/{categoriesid}', [CategoriesController::class, 'getCategories']);
Route::get('/suppliers/{suppliersid}', [SuppilersController::class, 'getSuppliers']);