<?php

namespace App\Http\Controllers\FeatureFlags;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('feature-flags.dashboard');
    }
}
