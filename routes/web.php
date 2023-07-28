<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('welcome');
});

Route::get('/roles', function () {
    return view('welcome');
});

Route::get('/users', function () {
    return view('welcome');
});

Route::get('/expense-categories', function () {
    return view('welcome');
});

Route::get('/expenses', function () {
    return view('welcome');
});

Route::get('/change-password', function () {
    return view('welcome');
});