<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'artistsCount' => DB::table('artists')->count(),
            'audioCount' => DB::table('listing')->where('ListingType', 'audio')->count(),
            'videoCount' => DB::table('listing')->where('ListingType', 'video')->count(),
            'draftCount' => DB::table('listing')->where('IsPublished', 'NO')->count(),
        ]);
    }
}
