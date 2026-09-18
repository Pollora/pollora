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
 * WordPress routes.
 *
 * Nothing to declare here for plain view rendering: the template hierarchy
 * resolves a request to the matching Blade view in
 * themes/{theme}/resources/views/ — home, single, page, archive, category,
 * author, search, taxonomy, 404 — through the FrontendController fallback.
 *
 * Use Route::wp() only when a request needs controller logic, middleware or a
 * named route.
 */
