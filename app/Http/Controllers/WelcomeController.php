<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('welcome'); // Laravel will load resources/views/welcome.blade.php
    }
}
