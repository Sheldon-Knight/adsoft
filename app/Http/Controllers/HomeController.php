<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {

        return redirect()->route('filament.auth.login');
        // return view('welcome');
    }
}
