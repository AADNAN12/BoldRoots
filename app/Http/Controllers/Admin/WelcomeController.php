<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{


    /**
     * Display the admin welcome page.
     */
    public function index()
    {
        return view('admin.welcome');
    }
}
