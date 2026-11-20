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
 * Nothing to declare here to render a template: the WordPress template
 * hierarchy resolves each request to the matching Blade view of the active
 * theme — home, single, page, archive, category, tag, author, date, search,
 * taxonomy, 404 — and falls back to index.blade.php.
 *
 * Use Route::wp() only when a request needs controller logic, middleware or a
 * named route.
 */
