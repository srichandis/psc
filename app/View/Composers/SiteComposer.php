<?php

namespace App\View\Composers;

use App\Models\Specialist;
use App\Models\Specialty;
use Illuminate\View\View;

class SiteComposer
{
    /**
     * Share the data needed by the global navigation and booking modal.
     *
     * The booking modal is rendered in the site layout, so its options must
     * be available on every public page rather than from a single controller.
     */
    public function compose(View $view): void
    {
        $view->with([
            'navSpecialties' => Specialty::query()->active()->ordered()->get(),

            'bookingSpecialties' => Specialty::query()->active()->ordered()->get(),

            'bookingSpecialists' => Specialist::query()
                ->with('specialty')
                ->active()
                ->ordered()
                ->get(),

            'bookingTimeWindows' => config('clinic.time_windows'),
        ]);
    }
}
