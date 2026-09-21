@extends('layouts.admin')

@section('heading', 'Dashboard')
@section('subheading', 'Booking requests and clinic activity at a glance')

@section('content')
    @php
        $tones = [
            'brand' => ['bg' => 'bg-brand-teal/10', 'text' => 'text-brand-teal'],
            'amber' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600'],
            'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
            'slate' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-500'],
        ];

        $barTones = [
            \App\Models\Appointment::STATUS_NEW => 'bg-amber-400',
            \App\Models\Appointment::STATUS_CONTACTED => 'bg-sky-400',
            \App\Models\Appointment::STATUS_BOOKED => 'bg-emerald-400',
            \App\Models\Appointment::STATUS_CANCELLED => 'bg-slate-300',
        ];
    @endphp

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $stat)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold tracking-wide text-slate-500 uppercase">{{ $stat['label'] }}</p>
                        <p class="mt-2 font-editorial text-3xl font-bold text-brand-teal">{{ $stat['value'] }}</p>
                        <p class="mt-1 text-[11px] text-slate-400">{{ $stat['hint'] }}</p>
                    </div>

                    <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl {{ $tones[$stat['tone']]['bg'] }} {{ $tones[$stat['tone']]['text'] }}">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 3v18h18" />
                            <path d="m7 14 4-4 3 3 5-6" />
                        </svg>
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Activity chart --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="font-editorial text-lg font-bold text-brand-teal">Requests received</h2>
                    <p class="text-xs text-slate-500">Booking inquiries over the last 7 days</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-semibold text-slate-600">
                    {{ array_sum(array_column($activity, 'count')) }} total
                </span>
            </div>

            <div class="mt-6 flex h-44 items-end gap-2 sm:gap-4">
                @foreach ($activity as $day)
                    <div class="group flex flex-1 flex-col items-center gap-2">
                        <span class="text-[11px] font-bold text-brand-teal">{{ $day['count'] }}</span>

                        <div class="flex h-32 w-full items-end justify-center">
                            <div class="w-full max-w-[3rem] rounded-t-lg bg-gradient-to-t from-brand-teal to-brand-accent transition-all duration-300 group-hover:opacity-80"
                                 style="height: {{ $day['height'] }}%"
                                 title="{{ $day['full'] }}: {{ $day['count'] }} request{{ $day['count'] === 1 ? '' : 's' }}"></div>
                        </div>

                        <span class="text-[11px] font-medium text-slate-500">{{ $day['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Status breakdown --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="font-editorial text-lg font-bold text-brand-teal">Pipeline</h2>
            <p class="text-xs text-slate-500">Where requests currently sit</p>

            <div class="mt-5 space-y-4">
                @foreach ($statusBreakdown as $row)
                    <div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-semibold text-slate-700">{{ $row['label'] }}</span>
                            <span class="text-slate-500">{{ $row['count'] }} &middot; {{ $row['percentage'] }}%</span>
                        </div>

                        <div class="mt-1.5 h-2 w-full overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full {{ $barTones[$row['status']] ?? 'bg-slate-300' }}"
                                 style="width: {{ max($row['percentage'], $row['count'] > 0 ? 4 : 0) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 space-y-2 border-t border-slate-100 pt-5 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-500">Specialists on site</span>
                    <span class="font-semibold text-slate-800">{{ $counts['specialists'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Specialties offered</span>
                    <span class="font-semibold text-slate-800">{{ $counts['specialties'] }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent requests --}}
    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between gap-4 border-b border-slate-100 p-6">
            <div>
                <h2 class="font-editorial text-lg font-bold text-brand-teal">Latest requests</h2>
                <p class="text-xs text-slate-500">The six most recent booking inquiries</p>
            </div>

            <a href="{{ route('admin.appointments.index') }}"
               class="inline-flex items-center gap-1.5 text-xs font-bold whitespace-nowrap text-brand-teal transition-colors hover:text-brand-accent">
                <span>View all</span>
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12h14M13 6l6 6-6 6" />
                </svg>
            </a>
        </div>

        @if ($recentAppointments->isEmpty())
            <p class="p-8 text-center text-sm text-slate-500">No booking requests have been received yet.</p>
        @else
            <div class="scrollbar-thin overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
                        <tr>
                            <th scope="col" class="px-6 py-3">Patient</th>
                            <th scope="col" class="px-6 py-3">Requested</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                            <th scope="col" class="px-6 py-3">Received</th>
                            <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($recentAppointments as $appointment)
                            <tr class="transition-colors hover:bg-slate-50/70">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-800">{{ $appointment->full_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $appointment->phone }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-medium text-slate-700">{{ $appointment->specialty ?? '—' }}</p>
                                    <p class="text-xs text-slate-500">{{ $appointment->specialist?->name ?? 'Any specialist' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <x-admin.status-badge :status="$appointment->status" />
                                </td>
                                <td class="px-6 py-4 text-xs whitespace-nowrap text-slate-500">
                                    {{ $appointment->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.appointments.show', $appointment) }}"
                                       class="text-xs font-semibold whitespace-nowrap text-brand-teal hover:text-brand-accent">
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
