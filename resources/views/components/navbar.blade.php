@php
    // Homepage anchors are prefixed with the site root so they still navigate
    // correctly when the visitor is on another page, such as /about.
    $home = route('home', absolute: false);

    $navLinks = [
        ['name' => 'Our Specialists', 'href' => $home.'#specialists'],
        ['name' => 'Specialties', 'href' => $home.'#specialties', 'dropdown' => true],
        ['name' => 'For Patients', 'href' => $home.'#visit-process'],
        ['name' => 'About Us', 'href' => route('about'), 'active' => request()->routeIs('about')],
        ['name' => 'Contact', 'href' => route('about').'#contact'],
    ];
@endphp

<header
    x-data="{
        scrolled: false,
        mobileOpen: false,
        specialtiesOpen: false,
        init() {
            const sync = () => { this.scrolled = window.scrollY > 20 };
            sync();
            window.addEventListener('scroll', sync, { passive: true });
        },
    }"
    x-cloak
    class="sticky top-0 z-40 w-full transition-all duration-300"
    :class="scrolled
        ? 'bg-white/95 backdrop-blur-md shadow-sm border-b border-slate-200/80 py-3'
        : 'bg-white/80 backdrop-blur-sm py-4 sm:py-5 border-b border-slate-200/50'"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">

            {{-- Brand --}}
            <a href="{{ route('home') }}" class="group flex items-center gap-3.5 focus:outline-none" aria-label="{{ config('clinic.name') }} home">
                {{-- Dark ink logo, for use on light backgrounds. --}}
                <img src="/images/logo.png" alt=""
                     class="h-10 w-10 flex-shrink-0 object-contain transition-transform duration-200 group-hover:scale-105 sm:h-11 sm:w-11">

                <div class="flex flex-col">
                    <span class="font-editorial text-2xl leading-none font-bold tracking-tight text-brand-teal sm:text-[28px]">
                        {{ config('clinic.short_name') }}
                    </span>
                    <span class="mt-0.5 text-[10px] leading-tight font-semibold tracking-[0.24em] text-brand-teal uppercase sm:text-[11px]">
                        Specialist Clinic
                    </span>
                </div>
            </a>

            {{-- Desktop navigation --}}
            <nav class="hidden items-center space-x-7 lg:flex" aria-label="Main navigation">
                @foreach ($navLinks as $link)
                    @if ($link['dropdown'] ?? false)
                        <div
                            class="relative"
                            @mouseenter="specialtiesOpen = true"
                            @mouseleave="specialtiesOpen = false"
                        >
                            <a href="{{ $link['href'] }}" class="inline-flex items-center gap-1 py-2 text-sm font-medium tracking-wide text-slate-700 transition-colors hover:text-brand-teal">
                                <span>{{ $link['name'] }}</span>
                                <svg class="h-3.5 w-3.5 transition-transform duration-200"
                                     :class="specialtiesOpen ? 'rotate-180 text-brand-accent' : 'text-slate-400'"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M6 9l6 6 6-6" />
                                </svg>
                            </a>

                            <div x-show="specialtiesOpen" x-cloak x-transition.opacity.duration.150ms class="absolute top-full left-0 z-50 w-64 pt-2">
                                <div class="rounded-xl border border-slate-100 bg-white p-2 shadow-xl ring-1 ring-black/5">
                                    <div class="px-3 py-1.5 text-[10px] font-bold tracking-wider text-brand-accent uppercase">
                                        Clinical Specialties
                                    </div>
                                    @foreach ($navSpecialties as $specialty)
                                        <a href="{{ $home }}#specialties"
                                           @click="specialtiesOpen = false"
                                           class="group flex flex-col rounded-lg px-3 py-2 transition-colors hover:bg-slate-50">
                                            <span class="text-xs font-semibold text-slate-800 group-hover:text-brand-teal">{{ $specialty->name }}</span>
                                            <span class="line-clamp-1 text-[11px] text-slate-500">{{ $specialty->short_description }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        @php $isActive = $link['active'] ?? false; @endphp

                        <a href="{{ $link['href'] }}"
                           @class([
                               'py-2 text-sm tracking-wide transition-colors',
                               'font-semibold text-brand-teal' => $isActive,
                               'font-medium text-slate-700 hover:text-brand-teal' => ! $isActive,
                           ])
                           @if ($isActive) aria-current="page" @endif>
                            {{ $link['name'] }}
                        </a>
                    @endif
                @endforeach
            </nav>

            {{-- Right hand actions --}}
            <div class="flex items-center gap-3 sm:gap-4">
                <a href="tel:{{ config('clinic.phone_link') }}"
                   class="group hidden items-center gap-2 rounded-lg px-2 py-1.5 text-sm font-semibold text-brand-teal transition-colors hover:text-brand-accent sm:inline-flex">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-50 text-brand-accent transition-colors group-hover:bg-brand-accent group-hover:text-white">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 5a2 2 0 0 1 2-2h3.28a1 1 0 0 1 .948.684l1.498 4.493a1 1 0 0 1-.502 1.21l-2.257 1.13a11.042 11.042 0 0 0 5.516 5.516l1.13-2.257a1 1 0 0 1 1.21-.502l4.493 1.498a1 1 0 0 1 .684.949V19a2 2 0 0 1-2 2h-1C9.716 21 3 14.284 3 6z" />
                        </svg>
                    </span>
                    <span>{{ config('clinic.phone') }}</span>
                </a>

                <button type="button"
                        @click="$dispatch('open-appointment-modal')"
                        class="inline-flex items-center justify-center rounded-md bg-brand-teal px-4 py-2 text-xs font-semibold text-white shadow-sm transition-all hover:bg-brand-dark hover:shadow active:scale-[0.98] sm:px-5 sm:py-2.5 sm:text-sm">
                    Book an Appointment
                </button>

                <button type="button"
                        @click="mobileOpen = ! mobileOpen"
                        class="rounded-lg p-2 text-slate-700 hover:bg-slate-100 hover:text-brand-teal lg:hidden"
                        aria-label="Toggle navigation menu">
                    <svg x-show="! mobileOpen" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <path d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileOpen" x-cloak class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <path d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile drawer --}}
    <div x-show="mobileOpen" x-cloak x-transition.opacity.duration.150ms class="border-b border-slate-200 bg-white px-4 pt-3 pb-6 shadow-xl lg:hidden">
        <div class="space-y-1">
            @foreach ($navLinks as $link)
                <a href="{{ $link['href'] }}"
                   @click="mobileOpen = false"
                   class="block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    {{ $link['name'] }}
                </a>
            @endforeach
        </div>

        <div class="mt-3 space-y-2 border-t border-slate-100 pt-3">
            <a href="tel:{{ config('clinic.phone_link') }}"
               class="flex w-full items-center justify-center gap-2 rounded-lg border border-slate-200 bg-slate-50 py-2.5 text-sm font-semibold text-brand-teal hover:bg-slate-100">
                <svg class="h-4 w-4 text-brand-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 5a2 2 0 0 1 2-2h3.28a1 1 0 0 1 .948.684l1.498 4.493a1 1 0 0 1-.502 1.21l-2.257 1.13a11.042 11.042 0 0 0 5.516 5.516l1.13-2.257a1 1 0 0 1 1.21-.502l4.493 1.498a1 1 0 0 1 .684.949V19a2 2 0 0 1-2 2h-1C9.716 21 3 14.284 3 6z" />
                </svg>
                <span>Call {{ config('clinic.phone') }}</span>
            </a>

            <button type="button"
                    @click="mobileOpen = false; $dispatch('open-appointment-modal')"
                    class="w-full rounded-lg bg-brand-teal py-2.5 text-center text-sm font-semibold text-white shadow hover:bg-brand-dark">
                Book an Appointment
            </button>
        </div>
    </div>
</header>
