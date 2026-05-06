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
 * Explicit WordPress routes (Route::wp).
 *
 * These take priority over the template hierarchy fallback.
 * Use them when you need controller logic, middleware, or named routes.
 * Simple view rendering can also be handled by the template hierarchy
 * (see FrontendController) — just create the corresponding Blade view.
 */
Route::wp('home', function () {
    return view('home');
});

Route::wp('singular', 'post', function () {
    return view('post');
});

Route::wp('page', function () {
    return view('page');
});
// Note: 404 and template hierarchy templates (archive, category, search, author,
// single-{post_type}) are handled by the FrontendController fallback.
// Just create the corresponding Blade view in themes/{theme}/resources/views/.
