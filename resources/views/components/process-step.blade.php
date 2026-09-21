@props([
    'number',
    'title',
    'description',
    'icon' => 'file',
    'isLast' => false,
])

<div class="relative">
    {{-- Connector between steps (large screens only) --}}
    @unless ($isLast)
        <span class="absolute top-6 left-[calc(50%+2.25rem)] hidden h-px w-[calc(100%-4.5rem)] bg-slate-200 lg:block" aria-hidden="true"></span>
    @endunless

    <div class="relative flex flex-col items-center text-center">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-teal/10 text-brand-teal ring-1 ring-brand-teal/15">
            <x-process-icon :name="$icon" />
        </div>

        <span class="mt-3 text-[11px] font-bold tracking-[0.2em] text-brand-accent">{{ $number }}</span>

        <h3 class="mt-1 font-editorial text-base font-bold text-brand-teal">{{ $title }}</h3>

        <p class="mt-1.5 max-w-[16rem] text-xs leading-relaxed text-slate-600">
            {!! $description !!}
        </p>
    </div>
</div>
