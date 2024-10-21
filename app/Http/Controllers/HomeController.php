<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Pollora\Models\Post;
use Pollora\Support\WordPress;

class HomeController extends Controller
{
    public function index()
    {

        return view('home');
    }
}
