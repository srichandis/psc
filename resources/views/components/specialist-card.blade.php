@props(['specialist'])

{{-- The whole card links to the specialist's own page, so the profile is a
     real, linkable URL rather than a modal that only exists in one place. --}}
<a href="{{ $specialist->profileUrl() }}"
   class="group flex flex-col overflow-hidden rounded-xl border border-slate-200/90 bg-white shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md focus-visible:ring-2 focus-visible:ring-brand-teal focus-visible:ring-offset-2 focus-visible:outline-none">

    <div class="relative aspect-4/3 w-full overflow-hidden bg-slate-100">
        @if ($specialist->image)
            <img src="{{ $specialist->image }}" alt="{{ $specialist->name }}" loading="lazy"
                 class="h-full w-full object-cover object-top transition-transform duration-300 group-hover:scale-105">
        @else
            <div class="flex h-full w-full items-center justify-center bg-slate-100 text-slate-300">
                <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <circle cx="12" cy="8" r="4" />
                    <path d="M4 21a8 8 0 0 1 16 0" />
                </svg>
            </div>
        @endif

        <div class="absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-transparent opacity-0 transition-opacity group-hover:opacity-100"></div>

        @if ($specialist->specialty)
            <span class="absolute top-3 left-3 rounded-full bg-white/95 px-2.5 py-1 text-[10px] font-bold tracking-wider text-brand-teal uppercase shadow-sm backdrop-blur-sm">
                {{ $specialist->specialty->name }}
            </span>
        @endif
    </div>

    <div class="flex flex-grow flex-col justify-between p-4 sm:p-5">
        <div class="space-y-1.5">
            <h3 class="font-editorial text-lg leading-snug font-bold text-brand-teal transition-colors group-hover:text-brand-accent sm:text-xl">
                {{ $specialist->name }}
            </h3>
            <p class="min-h-[32px] text-[11px] leading-normal font-medium tracking-tight text-slate-500">
                {{ $specialist->qualifications }}
            </p>
            <p class="pt-1 text-xs leading-snug font-semibold text-brand-accent">
                {{ $specialist->role }}
            </p>
        </div>

        <div class="mt-auto border-t border-slate-100 pt-4">
            <span class="inline-flex items-center gap-1 text-xs font-bold text-brand-teal transition-colors group-hover:text-brand-accent">
                <span>View Profile</span>
                <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12h14M13 6l6 6-6 6" />
                </svg>
            </span>
        </div>
    </div>
</a>
