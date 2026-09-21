<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HasBulkMessages;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    use HasBulkMessages;

    /**
     * List booking requests with optional status filter and search.
     */
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $search = $request->string('q')->toString();

        return view('admin.appointments.index', [
            'appointments' => Appointment::query()
                ->with(['specialist.specialty'])
                ->withStatus($status)
                ->search($search)
                ->latest()
                ->paginate(15)
                ->withQueryString(),

            'status' => $status,
            'search' => $search,

            'statusCounts' => Appointment::query()
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
        ]);
    }

    /**
     * Show a single booking request.
     */
    public function show(Appointment $appointment): View
    {
        $appointment->load(['specialist.specialty']);

        return view('admin.appointments.show', [
            'appointment' => $appointment,
        ]);
    }

    /**
     * Update the workflow status and internal notes.
     */
    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(Appointment::STATUSES))],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $appointment->update($validated);

        return back()->with('success', 'Appointment request updated.');
    }

    /**
     * Quick status change from the appointments table.
     */
    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(Appointment::STATUSES))],
        ]);

        $appointment->update($validated);

        return back()->with('success', "Marked as {$appointment->status_label}.");
    }

    /**
     * Delete a booking request.
     */
    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();

        return redirect()
            ->route('admin.appointments.index')
            ->with('success', 'Appointment request deleted.');
    }

    /**
     * Apply a status change or deletion to several requests at once.
     */
    public function bulk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
            'action' => ['required', Rule::in(['status', 'delete'])],
            'status' => ['required_if:action,status', Rule::in(array_keys(Appointment::STATUSES))],
        ], [
            'ids.required' => 'Select at least one booking request first.',
            'status.required_if' => 'Choose the status to apply.',
        ]);

        $appointments = Appointment::query()->whereKey($validated['ids']);

        if ($validated['action'] === 'delete') {
            $deleted = $appointments->delete();

            return back()->with('success', $this->bulkMessage($deleted, 'request', 'deleted'));
        }

        $updated = $appointments->update(['status' => $validated['status']]);

        $label = Appointment::STATUSES[$validated['status']];

        return back()->with('success', $this->bulkMessage($updated, 'request', "marked as {$label}"));
    }
}
