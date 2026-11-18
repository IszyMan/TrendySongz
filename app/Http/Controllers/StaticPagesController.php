<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class StaticPagesController extends Controller
{
    public function about(): View
    {
        return view('static.about-us');
    }

    public function contact(): View
    {
        return view('static.contact-us');
    }

    public function dmca(): View
    {
        return view('static.dmca');
    }
}