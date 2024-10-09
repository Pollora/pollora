<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Pollen\Models\Post;
use Pollen\Support\WordPress;

class HomeController extends Controller
{
    public function index()
    {

        return view('home');
    }
}
