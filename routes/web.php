<?php

declare(strict_types=1);

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

/**
 * WordPress routes
 */
Route::wp('home', function () {
    return view('home');
});

Route::wp('single', function () {
    return view('post');
});

Route::wp('page', function () {
    return view('page');
});

Route::wp('404', function () {
    return response()->view('errors.404', [], 404);
});
