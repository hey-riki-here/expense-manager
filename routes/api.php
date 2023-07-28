<?php

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

Route::resource('role', App\Http\Controllers\RoleController::class)->only(['index','store', 'show', 'update', 'destroy']);

Route::resource('user', App\Http\Controllers\UserController::class)->only(['index','store', 'show', 'update', 'destroy']);

Route::resource('expense-category', App\Http\Controllers\ExpenseCategoryController::class)->only(['index','store', 'show', 'update', 'destroy']);

Route::resource('expense', App\Http\Controllers\ExpenseController::class)->only(['index','store', 'show', 'update', 'destroy']);

Route::resource('login', App\Http\Controllers\LoginController::class)->only(['index','store', 'show', 'update', 'destroy']);