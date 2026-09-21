@extends('layouts.admin')

@section('heading', 'Appointments')
@section('subheading', 'Review and triage booking requests submitted from the website')

@section('header-actions')
    <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer"
       class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-brand-teal shadow-sm transition-colors hover:bg-slate-50">
        <span>Public site</span>
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
            <path d="M15 3h6v6M10 14 21 3" />
        </svg>
    </a>
@endsection

@section('content')
    @php
        $total = $statusCounts->sum();

        $filters = ['' => ['label' => 'All requests', 'count' => $total]];

        foreach (\App\Models\Appointment::STATUSES as $value => $label) {
            $filters[$value] = ['label' => $label, 'count' => (int) $statusCounts->get($value, 0)];
        }
    @endphp

    {{-- Filters --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
         x-data="bulkSelection({ ids: @js($appointments->getCollection()->pluck('id')) })">
        <div class="flex flex-col gap-4 border-b border-slate-100 p-4 sm:p-5 lg:flex-row lg:items-center lg:justify-between">

            <div class="flex flex-wrap gap-2">
                @foreach ($filters as $value => $filter)
                    <a href="{{ route('admin.appointments.index', array_filter(['status' => $value, 'q' => $search ?: null])) }}"
                       @class([
                           'inline-flex items-center gap-2 rounded-full border px-3.5 py-1.5 text-xs font-semibold transition-colors',
                           'border-brand-teal bg-brand-teal text-white shadow-sm' => $status === $value,
                           'border-slate-200 bg-white text-slate-600 hover:bg-slate-50' => $status !== $value,
                       ])>
                        <span>{{ $filter['label'] }}</span>
                        <span @class([
                            'rounded-full px-1.5 py-0.5 text-[10px] font-bold',
                            'bg-white/20 text-white' => $status === $value,
                            'bg-slate-100 text-slate-500' => $status !== $value,
                        ])>{{ $filter['count'] }}</span>
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('admin.appointments.index') }}" class="flex gap-2">
                @if ($status !== '')
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif

                <label for="appointment-search" class="sr-only">Search appointments</label>
                <div class="relative flex-1">
                    <svg class="absolute top-2.5 left-3 h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7" />
                        <path d="m20 20-3.5-3.5" />
                    </svg>
                    <input id="appointment-search" type="search" name="q" value="{{ $search }}"
                           placeholder="Search name, phone, email…"
                           class="w-full rounded-lg border border-slate-300 py-2 pr-3 pl-9 text-xs text-slate-800 transition focus:border-transparent focus:ring-2 focus:ring-brand-teal focus:outline-none lg:w-64">
                </div>

                <button type="submit"
                        class="rounded-lg bg-brand-teal px-4 py-2 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-brand-dark">
                    Search
                </button>
            </form>
        </div>

        {{-- Bulk actions --}}
        <x-admin.bulk-bar :action="route('admin.appointments.bulk')" label="request">
            <label for="bulk-appointment-status" class="sr-only">Status to apply</label>
            <select id="bulk-appointment-status" name="status"
                    class="rounded-lg border border-slate-300 bg-white py-1.5 pr-7 pl-2.5 text-xs font-medium text-slate-700 transition focus:border-transparent focus:ring-2 focus:ring-brand-teal focus:outline-none">
                @foreach (\App\Models\Appointment::STATUSES as $value => $statusLabel)
                    <option value="{{ $value }}">{{ $statusLabel }}</option>
                @endforeach
            </select>

            <button type="submit" name="action" value="status"
                    class="rounded-lg bg-brand-teal px-3.5 py-1.5 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-brand-dark">
                Apply status
            </button>

            <button type="submit" name="action" value="delete"
                    onclick="return confirm('Delete the selected booking requests? This cannot be undone.')"
                    class="rounded-lg border border-red-300 bg-white px-3.5 py-1.5 text-xs font-semibold text-red-700 transition-colors hover:bg-red-50">
                Delete selected
            </button>
        </x-admin.bulk-bar>

        @if ($appointments->isEmpty())
            <div class="p-12 text-center">
                <p class="font-editorial text-lg font-bold text-brand-teal">No booking requests found</p>
                <p class="mt-1 text-sm text-slate-500">
                    @if ($search !== '' || $status !== '')
                        Try clearing the filters or searching for something else.
                    @else
                        Requests submitted from the website will appear here.
                    @endif
                </p>

                @if ($search !== '' || $status !== '')
                    <a href="{{ route('admin.appointments.index') }}"
                       class="mt-4 inline-block rounded-lg border border-slate-200 px-4 py-2 text-xs font-semibold text-brand-teal hover:bg-slate-50">
                        Clear filters
                    </a>
                @endif
            </div>
        @else
            <div class="scrollbar-thin overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
                        <tr>
                            <th scope="col" class="w-12 px-5 py-3">
                                <x-admin.select-checkbox label="Select all requests on this page" />
                            </th>
                            <th scope="col" class="px-5 py-3">Patient</th>
                            <th scope="col" class="px-5 py-3">Requested</th>
                            <th scope="col" class="px-5 py-3">Referral</th>
                            <th scope="col" class="px-5 py-3">Preferred</th>
                            <th scope="col" class="px-5 py-3">Status</th>
                            <th scope="col" class="px-5 py-3">Received</th>
                            <th scope="col" class="px-5 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach ($appointments as $appointment)
                            <tr class="transition-colors hover:bg-slate-50/70"
                                :class="isSelected({{ $appointment->id }}) && 'bg-brand-teal/5'">
                                <td class="px-5 py-4">
                                    <x-admin.select-checkbox :id="$appointment->id"
                                        label="Select the request from {{ $appointment->full_name }}" />
                                </td>

                                <td class="px-5 py-4">
                                    <a href="{{ route('admin.appointments.show', $appointment) }}"
                                       class="font-semibold text-slate-800 hover:text-brand-teal">
                                        {{ $appointment->full_name }}
                                    </a>
                                    <p class="mt-0.5 text-xs text-slate-500">{{ $appointment->phone }}</p>
                                    @if ($appointment->email)
                                        <p class="text-xs text-slate-400">{{ $appointment->email }}</p>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-xs font-medium text-slate-700">{{ $appointment->specialty ?? '—' }}</p>
                                    <p class="text-xs text-slate-500">
                                        {{ $appointment->specialist?->name ?? 'Any available specialist' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <span @class([
                                        'inline-flex rounded-md border px-2 py-0.5 text-[11px] font-semibold whitespace-nowrap',
                                        'border-emerald-200 bg-emerald-50 text-emerald-700' => $appointment->referral_status === 'yes',
                                        'border-amber-200 bg-amber-50 text-amber-700' => $appointment->referral_status === 'pending',
                                        'border-slate-200 bg-slate-50 text-slate-600' => $appointment->referral_status === 'no',
                                    ])>
                                        {{ $appointment->referral_label }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-xs whitespace-nowrap text-slate-600">
                                    @if ($appointment->preferred_date)
                                        <p class="font-medium">{{ $appointment->preferred_date->format('j M Y') }}</p>
                                    @else
                                        <p class="text-slate-400">No date given</p>
                                    @endif
                                    <p class="text-slate-500">{{ $appointment->preferred_time ?? '—' }}</p>
                                </td>

                                {{-- Inline status update --}}
                                <td class="px-5 py-4">
                                    <form method="POST" action="{{ route('admin.appointments.status', $appointment) }}">
                                        @csrf
                                        @method('PATCH')

                                        <label class="sr-only" for="status-{{ $appointment->id }}">Status for {{ $appointment->full_name }}</label>
                                        <select id="status-{{ $appointment->id }}" name="status" onchange="this.form.submit()"
                                                class="cursor-pointer rounded-lg border border-slate-300 bg-white py-1.5 pr-7 pl-2.5 text-xs font-medium text-slate-700 transition focus:border-transparent focus:ring-2 focus:ring-brand-teal focus:outline-none">
                                            @foreach (\App\Models\Appointment::STATUSES as $value => $label)
                                                <option value="{{ $value }}" @selected($appointment->status === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>

                                        <noscript>
                                            <button type="submit" class="mt-1 block text-xs font-semibold text-brand-teal">Update</button>
                                        </noscript>
                                    </form>
                                </td>

                                <td class="px-5 py-4 text-xs whitespace-nowrap text-slate-500">
                                    {{ $appointment->created_at->format('j M Y') }}
                                    <p class="text-slate-400">{{ $appointment->created_at->format('g:ia') }}</p>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.appointments.show', $appointment) }}"
                                           class="text-xs font-semibold whitespace-nowrap text-brand-teal hover:text-brand-accent">
                                            Review
                                        </a>

                                        <form method="POST" action="{{ route('admin.appointments.destroy', $appointment) }}"
                                              onsubmit="return confirm('Delete the request from {{ $appointment->full_name }}? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-xs font-semibold whitespace-nowrap text-slate-400 transition-colors hover:text-red-600">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($appointments->hasPages())
                <div class="border-t border-slate-100 p-4">
                    {{ $appointments->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
