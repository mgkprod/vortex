<?php

namespace App\Http\Controllers;

class OverviewController extends Controller
{
    public function __invoke()
    {
        return inertia('overview');
    }
}
