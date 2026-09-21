<?php

namespace App\Http\Controllers;

use App\Models\Specialist;
use App\Models\Specialty;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * The four step "your visit" journey shown on the homepage.
     */
    protected const PROCESS_STEPS = [
        [
            'number' => '01',
            'title' => 'Get a Referral',
            'description' => 'A referral from your GP or another specialist is required.',
            'icon' => 'file',
        ],
        [
            'number' => '02',
            'title' => 'Book Your Appointment',
            'description' => 'Call our friendly reception team on (07) 5500 0536, or send a request online.',
            'icon' => 'phone',
        ],
        [
            'number' => '03',
            'title' => 'Prepare for Your Visit',
            'description' => 'Bring your Medicare card and referral if not already provided.',
            'icon' => 'calendar',
        ],
        [
            'number' => '04',
            'title' => 'Meet Your Specialist',
            'description' => 'Your specialist will assess your concerns and discuss next steps.',
            'icon' => 'users',
        ],
    ];

    /**
     * Display the clinic homepage.
     */
    public function __invoke(): View
    {
        return view('home', [
            'specialists' => Specialist::query()
                ->with('specialty')
                ->active()
                ->ordered()
                ->get(),

            'specialties' => Specialty::query()
                ->with(['specialists' => fn ($query) => $query->active()->ordered()])
                ->withCount('specialists')
                ->active()
                ->ordered()
                ->get(),

            'processSteps' => self::PROCESS_STEPS,
        ]);
    }
}
