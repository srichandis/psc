@props(['status'])

@php
    $styles = [
        \App\Models\Appointment::STATUS_NEW => 'bg-amber-50 text-amber-700 border-amber-200',
        \App\Models\Appointment::STATUS_CONTACTED => 'bg-sky-50 text-sky-700 border-sky-200',
        \App\Models\Appointment::STATUS_BOOKED => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        \App\Models\Appointment::STATUS_CANCELLED => 'bg-slate-100 text-slate-600 border-slate-200',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[11px] font-semibold whitespace-nowrap '.($styles[$status] ?? $styles[\App\Models\Appointment::STATUS_NEW])]) }}>
    <span class="h-1.5 w-1.5 rounded-full bg-current" aria-hidden="true"></span>
    {{ \App\Models\Appointment::STATUSES[$status] ?? ucfirst((string) $status) }}
</span>
