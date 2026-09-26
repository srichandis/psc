@extends('layouts.app')

@section('title', config('clinic.name').' | Specialist Care with a Personal Approach')
@section('meta_description', 'Expert medical care across neurology, psychiatry, psychology, endocrinology and nephrology at '.config('clinic.address.full').'.')

@section('content')
    <div id="home" class="space-y-16 sm:space-y-24">

        {{-- ========================================================= --}}
        {{-- HERO                                                       --}}
        {{-- ========================================================= --}}
        <section class="relative overflow-hidden pt-6 pb-8 sm:pt-10 lg:pt-14">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 items-center gap-8 lg:grid-cols-12 lg:gap-12">

                    <div class="z-10 space-y-6 sm:space-y-8 lg:col-span-7">
                        <div class="max-w-2xl space-y-4">
                            <h1 class="scroll-mt-24 font-editorial text-4xl leading-[1.12] font-bold tracking-tight text-brand-teal sm:text-5xl lg:text-[58px]">
                                {{ config('clinic.slogan') }}
                            </h1>
                            <p class="max-w-xl text-base leading-relaxed font-normal text-slate-600 sm:text-lg">
                                Expert medical care across neurology, psychiatry, psychology, endocrinology and nephrology.
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3.5 pt-1">
                            <button type="button" @click="$dispatch('open-appointment-modal')"
                                    class="group inline-flex items-center gap-2 rounded-md bg-brand-teal px-6 py-3.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-brand-dark hover:shadow active:scale-[0.98]">
                                <span>Book an Appointment</span>
                                <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 12h14M13 6l6 6-6 6" />
                                </svg>
                            </button>

                            <a href="tel:{{ config('clinic.phone_link') }}"
                               class="inline-flex items-center gap-2 rounded-md border border-brand-teal/40 bg-white px-5 py-3.5 text-sm font-semibold text-brand-teal transition-all hover:bg-slate-50 active:scale-[0.98]">
                                <svg class="h-4 w-4 text-brand-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 5a2 2 0 0 1 2-2h3.28a1 1 0 0 1 .948.684l1.498 4.493a1 1 0 0 1-.502 1.21l-2.257 1.13a11.042 11.042 0 0 0 5.516 5.516l1.13-2.257a1 1 0 0 1 1.21-.502l4.493 1.498a1 1 0 0 1 .684.949V19a2 2 0 0 1-2 2h-1C9.716 21 3 14.284 3 6z" />
                                </svg>
                                <span>Call {{ config('clinic.phone') }}</span>
                            </a>
                        </div>

                        {{-- Address & parking --}}
                        <div class="flex max-w-xl items-start gap-3.5 pt-4 text-xs text-slate-600 sm:text-sm">
                            <div class="mt-0.5 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-brand-teal/10 text-brand-teal">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-brand-teal">{{ config('clinic.address.full') }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">Free on-site parking for patients, including accessible spaces.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Hero image with script signature --}}
                    <div class="relative mt-4 lg:col-span-5 lg:mt-0">
                        <div class="group relative aspect-5/4 overflow-hidden rounded-2xl border border-white/80 shadow-lg sm:aspect-4/3 lg:aspect-5/4">
                            <img src="https://images.unsplash.com/photo-1622042795081-c27996872dbc?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                                 alt="Specialist consultation at {{ config('clinic.name') }}"
                                 class="h-full w-full object-cover object-center transition-transform duration-700 group-hover:scale-[1.02]">

                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-brand-teal/60 via-transparent to-transparent"></div>

                            <div class="pointer-events-none absolute right-5 bottom-4 text-right select-none">
                                <p class="font-script text-2xl leading-tight tracking-wide text-white drop-shadow-md sm:text-3xl">
                                    People.<br>
                                    Specialist care.<br>
                                    Brighter tomorrows.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ========================================================= --}}
        {{-- OUR SPECIALISTS                                            --}}
        {{-- ========================================================= --}}
        <section id="specialists" class="scroll-mt-24 py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-8 flex flex-col justify-between gap-4 sm:mb-10 md:flex-row md:items-end">
                    <div class="max-w-2xl space-y-2">
                        <span class="block text-xs font-bold tracking-[0.2em] text-brand-accent uppercase">Our specialists</span>
                        <h2 class="font-editorial text-3xl font-bold text-brand-teal sm:text-4xl">Meet Our Specialists</h2>
                        <p class="pt-1 text-sm leading-relaxed text-slate-600">
                            Our multidisciplinary team provides specialist assessment and care across a range of neurological,
                            psychological, psychiatric, endocrine and kidney conditions.
                        </p>
                    </div>
                </div>

                @if ($specialists->isEmpty())
                    <p class="rounded-xl border border-slate-200 bg-white p-8 text-center text-sm text-slate-500">
                        Our specialist directory is being updated. Please call reception on {{ config('clinic.phone') }}.
                    </p>
                @else
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3">
                        @foreach ($specialists as $specialist)
                            <x-specialist-card :specialist="$specialist" />
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        {{-- ========================================================= --}}
        {{-- OUR SPECIALTIES                                            --}}
        {{-- ========================================================= --}}
        <section id="specialties" class="scroll-mt-24 py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-8 flex flex-col justify-between gap-4 sm:mb-10 md:flex-row md:items-end">
                    <div class="space-y-2">
                        <span class="block text-xs font-bold tracking-[0.2em] text-brand-accent uppercase">Our specialties</span>
                        <h2 class="font-editorial text-3xl font-bold text-brand-teal sm:text-4xl">Comprehensive Specialist Care</h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3">
                    @foreach ($specialties as $specialty)
                        <x-specialty-card :specialty="$specialty" />
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ========================================================= --}}
        {{-- YOUR VISIT                                                 --}}
        {{-- ========================================================= --}}
        <section id="visit-process" class="scroll-mt-24 py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-10 flex flex-col justify-between gap-4 sm:mb-12 md:flex-row md:items-end">
                    <div class="space-y-2">
                        <span class="block text-xs font-bold tracking-[0.2em] text-brand-accent uppercase">Your visit</span>
                        <h2 class="font-editorial text-3xl font-bold text-brand-teal sm:text-4xl">A Simple Appointment Process</h2>
                    </div>

                    <button type="button" @click="$dispatch('open-appointment-modal')"
                            class="inline-flex items-center gap-1.5 text-xs font-bold whitespace-nowrap text-brand-teal transition-colors hover:text-brand-accent">
                        <span>Start your request online</span>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-8 rounded-2xl border border-slate-200/80 bg-white/70 p-8 sm:grid-cols-2 lg:grid-cols-4 lg:gap-4">
                    @foreach ($processSteps as $index => $step)
                        <x-process-step
                            :number="$step['number']"
                            :title="$step['title']"
                            :description="$step['description']"
                            :icon="$step['icon']"
                            :is-last="$index === count($processSteps) - 1"
                        />
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ========================================================= --}}
        {{-- LOCATION                                                   --}}
        {{-- ========================================================= --}}
        <section id="location" class="scroll-mt-24 py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 items-stretch gap-6 lg:grid-cols-12">

                    {{-- Location details --}}
                    <div class="flex flex-col justify-between rounded-2xl border border-slate-200/90 bg-white p-7 shadow-sm lg:col-span-4">
                        <div class="space-y-4">
                            <h3 class="font-editorial text-2xl font-bold text-brand-teal sm:text-3xl">Our Location</h3>

                            <div class="flex items-start gap-3 text-sm font-semibold text-brand-teal">
                                <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-brand-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{{ config('clinic.address.line1') }},<br>{{ config('clinic.address.line2') }}</span>
                            </div>

                            <p class="pt-1 text-xs leading-relaxed text-slate-600">{{ config('clinic.parking') }}</p>

                            <dl class="space-y-1.5 border-t border-slate-100 pt-4 text-xs">
                                @foreach (config('clinic.hours') as $entry)
                                    <div class="flex justify-between gap-3">
                                        <dt class="text-slate-500">{{ $entry['days'] }}</dt>
                                        <dd class="font-semibold text-slate-700">{{ $entry['times'] }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>

                        <div class="pt-6">
                            <a href="{{ config('clinic.directions_url') }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-brand-teal px-6 py-3 text-xs font-bold text-white shadow-sm transition-all hover:bg-brand-dark active:scale-[0.98] sm:w-auto">
                                <span>Get Directions</span>
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 12h14M13 6l6 6-6 6" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    {{-- Clinic photo --}}
                    <div class="relative min-h-[260px] overflow-hidden rounded-2xl border border-slate-200/90 shadow-sm lg:col-span-4">
                        <img src="/images/clinic.png"
                             alt="{{ config('clinic.name') }} at {{ config('clinic.address.full') }}"
                             loading="lazy" decoding="async"
                             class="h-full w-full object-cover object-center">

                        <div class="absolute inset-0 flex items-center justify-center bg-black/20 p-4">
                            <div class="rounded-lg border border-white bg-white/90 px-4 py-2.5 text-center shadow-md backdrop-blur-sm">
                                <span class="block font-editorial text-sm font-bold text-brand-teal">{{ config('clinic.short_name') }}</span>
                                <span class="block text-[9px] font-semibold tracking-wider text-slate-600 uppercase">Specialist Clinic</span>
                            </div>
                        </div>
                    </div>

                    {{-- Map --}}
                    <div class="relative min-h-[260px] overflow-hidden rounded-2xl border border-slate-200/90 bg-slate-100 shadow-sm lg:col-span-4">
                        <iframe title="{{ config('clinic.name') }} location map"
                                class="h-full min-h-[280px] w-full border-0"
                                loading="lazy"
                                allowfullscreen
                                referrerpolicy="no-referrer-when-downgrade"
                                src="{{ config('clinic.map_embed_url') }}"></iframe>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
