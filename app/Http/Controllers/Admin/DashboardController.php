<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Specialist;
use App\Models\Specialty;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the admin overview dashboard.
     */
    public function __invoke(): View
    {
        $statusCounts = Appointment::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboard', [
            'stats' => [
                [
                    'label' => 'Total requests',
                    'value' => Appointment::count(),
                    'hint' => 'All time',
                    'tone' => 'brand',
                ],
                [
                    'label' => 'Awaiting action',
                    'value' => (int) $statusCounts->get(Appointment::STATUS_NEW, 0),
                    'hint' => 'Not yet contacted',
                    'tone' => 'amber',
                ],
                [
                    'label' => 'Confirmed',
                    'value' => (int) $statusCounts->get(Appointment::STATUS_BOOKED, 0),
                    'hint' => 'Booked with patient',
                    'tone' => 'emerald',
                ],
                [
                    'label' => 'This week',
                    'value' => Appointment::where('created_at', '>=', Carbon::now()->startOfWeek())->count(),
                    'hint' => 'New since Monday',
                    'tone' => 'slate',
                ],
            ],

            'statusBreakdown' => $this->statusBreakdown($statusCounts),
            'activity' => $this->dailyActivity(),
            'recentAppointments' => Appointment::query()
                ->with(['specialist.specialty'])
                ->latest()
                ->take(6)
                ->get(),

            'counts' => [
                'specialists' => Specialist::count(),
                'specialties' => Specialty::count(),
            ],
        ]);
    }

    /**
     * Build the status breakdown with percentages for the dashboard bars.
     *
     * @param  Collection<string, int|string>  $statusCounts
     * @return array<int, array<string, mixed>>
     */
    protected function statusBreakdown(Collection $statusCounts): array
    {
        $total = max(1, (int) $statusCounts->sum());

        return collect(Appointment::STATUSES)
            ->map(fn (string $label, string $status) => [
                'status' => $status,
                'label' => $label,
                'count' => (int) $statusCounts->get($status, 0),
                'percentage' => round(((int) $statusCounts->get($status, 0) / $total) * 100),
            ])
            ->values()
            ->all();
    }

    /**
     * Booking requests received per day over the last seven days.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function dailyActivity(): array
    {
        $counts = Appointment::query()
            ->where('created_at', '>=', Carbon::today()->subDays(6))
            ->selectRaw('date(created_at) as day, count(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        $peak = max(1, (int) $counts->max());

        return collect(range(6, 0))
            ->map(function (int $daysAgo) use ($counts, $peak) {
                $date = Carbon::today()->subDays($daysAgo);
                $total = (int) $counts->get($date->toDateString(), 0);

                return [
                    'label' => $date->format('D'),
                    'full' => $date->format('j M'),
                    'count' => $total,
                    'height' => max(6, (int) round(($total / $peak) * 100)),
                ];
            })
            ->values()
            ->all();
    }
}
