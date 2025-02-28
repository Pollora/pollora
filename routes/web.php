<?php

declare(strict_types=1);

use App\Http\Controllers\HomeController;
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

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

/**
 * WooCommerce routes.
 */
Route::any('shop', function () {
    return view('woocommerce.archive');
});

Route::any('product_category', function () {
    return view('woocommerce.archive');
});

Route::any('product_tag', function () {
    return view('woocommerce.archive');
});

Route::any('product', function () {
    return view('woocommerce.single');
});

/**
 * WordPress routes
 */
Route::any('single', function () {
    return view('post');
});

Route::any('page', function () {
    return view('page');
});