<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;

class AppointmentController extends Controller
{
    /**
     * Store a booking request submitted from the public website.
     */
    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $appointment = Appointment::create([
            ...$request->safe()->except('specialist_id'),
            'specialist_id' => $request->validated('specialist_id'),
            'status' => Appointment::STATUS_NEW,
        ]);

        // Flashing the patient's name lets the confirmation modal greet them
        // by name without exposing the record id in the URL.
        return redirect()
            ->route('home')
            ->with('appointment_booked', $appointment->full_name);
    }
}
