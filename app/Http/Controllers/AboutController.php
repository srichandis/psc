<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AboutController extends Controller
{
    /**
     * Display the "About Us" page: location, preparing for a visit,
     * booking, referral pathways and contact details.
     */
    public function __invoke(): View
    {
        return view('about');
    }
}
