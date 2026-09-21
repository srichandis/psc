@extends('layouts.app')

@section('title', $specialist->name.' — '.$specialist->role.' | '.config('clinic.name'))
{{-- Str::limit() must not be handed null: it raises a deprecation in PHP 8.4. --}}
@section('meta_description', $specialist->bio ? \Illuminate\Support\Str::limit($specialist->bio, 155) : $specialist->name.' — '.$specialist->role.' at '.config('clinic.name').', Coomera QLD.')

@php
    $home = route('home', absolute: false);

    $heading = 'font-editorial text-xl font-bold text-brand-teal sm:text-2xl';
    $prose = 'space-y-4 text-sm leading-relaxed text-slate-600 sm:text-base';
    $checkIcon = 'mt-0.5 h-4 w-4 flex-shrink-0 text-brand-accent';
    $bookPayload = ['specialistId' => $specialist->id, 'specialty' => $specialist->specialty?->name];
@endphp

@push('styles')
    {{-- Data comes from the controller: a literal @context key in a template is
         parsed by Blade as a directive. --}}
    <script type="application/ld+json">
        {!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">

        {{-- Breadcrumb --}}
        <nav class="text-xs text-slate-500" aria-label="Breadcrumb">
            <ol class="flex flex-wrap items-center gap-2">
                <li><a href="{{ $home }}" class="transition-colors hover:text-brand-teal">Home</a></li>
                <li aria-hidden="true" class="text-slate-300">/</li>
                <li><a href="{{ $home }}#specialists" class="transition-colors hover:text-brand-teal">Our Specialists</a></li>
                <li aria-hidden="true" class="text-slate-300">/</li>
                <li class="font-semibold text-slate-700" aria-current="page">{{ $specialist->name }}</li>
            </ol>
        </nav>

        {{-- ========================================================= --}}
        {{-- Hero                                                        --}}
        {{-- ========================================================= --}}
        <header class="mt-5 overflow-hidden rounded-2xl border border-slate-200/90 bg-white shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-[300px_1fr]">

                {{-- Headshot --}}
                <div class="relative aspect-4/3 w-full overflow-hidden bg-slate-100 md:aspect-auto md:h-full">
                    @if ($specialist->image)
                        <img src="{{ $specialist->image }}" alt="{{ $specialist->name }}"
                             class="h-full w-full object-cover object-top">
                    @else
                        <div class="flex h-full min-h-64 w-full items-center justify-center text-slate-300">
                            <svg class="h-14 w-14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 21a8 8 0 0 1 16 0" />
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Identity --}}
                <div class="flex flex-col justify-center gap-4 bg-brand-teal p-7 text-white sm:p-9">
                    @if ($specialist->specialty)
                        <a href="{{ $home }}#specialties"
                           class="inline-flex w-fit items-center gap-1.5 rounded-full border border-teal-700/50 bg-teal-800/80 px-3 py-1 text-[10px] font-bold tracking-wider text-teal-100 uppercase transition-colors hover:bg-teal-700/80">
                            {{ $specialist->specialty->name }}
                        </a>
                    @endif

                    <div class="space-y-2">
                        <h1 class="font-editorial text-3xl leading-tight font-bold sm:text-4xl">{{ $specialist->name }}</h1>
                        <p class="text-xs font-medium tracking-wide text-teal-200">{{ $specialist->qualifications }}</p>
                        <p class="text-sm font-semibold text-white/90">{{ $specialist->role }}</p>
                    </div>

                    @if ($specialist->consulting_days)
                        <p class="inline-flex w-fit items-center gap-2 rounded-lg bg-white/10 px-3 py-2 text-xs font-semibold text-teal-50">
                            <svg class="h-4 w-4 flex-shrink-0 text-teal-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="4" width="18" height="18" rx="2" />
                                <path d="M16 2v4M8 2v4M3 10h18" />
                            </svg>
                            <span>Consulting {{ $specialist->consulting_days }}</span>
                        </p>
                    @endif

                    <div class="flex flex-wrap gap-3">
                        <button type="button"
                                @click="$dispatch('open-appointment-modal', @js($bookPayload))"
                                class="inline-flex items-center gap-2 rounded-lg bg-white px-5 py-2.5 text-xs font-bold text-brand-teal shadow-sm transition-all hover:bg-teal-50 active:scale-[0.98]">
                            <span>Book with {{ $specialist->name }}</span>
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14M13 6l6 6-6 6" />
                            </svg>
                        </button>

                        <a href="tel:{{ config('clinic.phone_link') }}"
                           class="inline-flex items-center gap-2 rounded-lg border border-white/30 px-5 py-2.5 text-xs font-bold text-white transition-colors hover:bg-white/10">
                            Call {{ config('clinic.phone') }}
                        </a>
                    </div>
                </div>
            </div>
        </header>

        {{-- ========================================================= --}}
        {{-- Body                                                        --}}
        {{-- ========================================================= --}}
        <div class="mt-10 grid grid-cols-1 gap-10 lg:mt-14 lg:grid-cols-12 lg:gap-12">

            {{-- Main column --}}
            <div class="space-y-10 lg:col-span-8">
                @if ($specialist->bio)
                    <section>
                        <h2 class="{{ $heading }}">About {{ $specialist->name }}</h2>
                        <p class="{{ $prose }} mt-4">{{ $specialist->bio }}</p>
                    </section>
                @endif

                @foreach ($specialist->profileBlocks() as $block)
                    <section>
                        @if ($block['heading'])
                            <h2 class="{{ $heading }}">{{ $block['heading'] }}</h2>
                        @endif

                        @if ($block['type'] === 'paragraphs')
                            <div class="{{ $prose }} {{ $block['heading'] ? 'mt-4' : '' }}">
                                @foreach ($block['items'] as $paragraph)
                                    <p>{{ $paragraph }}</p>
                                @endforeach
                            </div>
                        @else
                            <ul class="{{ $block['heading'] ? 'mt-4' : '' }} space-y-2.5">
                                @foreach ($block['items'] as $item)
                                    <li class="flex items-start gap-3 text-sm text-slate-700 sm:text-base">
                                        <svg class="{{ $checkIcon }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <circle cx="12" cy="12" r="10" />
                                            <path d="m8 12 3 3 5-6" />
                                        </svg>
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </section>
                @endforeach

                @if (! empty($specialist->special_interests))
                    <section>
                        <h2 class="{{ $heading }}">Special Interests</h2>
                        <div class="mt-4 grid grid-cols-1 gap-2.5 sm:grid-cols-2">
                            @foreach ($specialist->special_interests as $interest)
                                <div class="flex items-start gap-3 rounded-xl border border-slate-200/80 bg-white p-3.5 text-xs text-slate-700 shadow-sm sm:text-sm">
                                    <svg class="{{ $checkIcon }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="m8 12 3 3 5-6" />
                                    </svg>
                                    <span>{{ $interest }}</span>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- Referral reminder --}}
                <section class="rounded-2xl border border-emerald-200 bg-emerald-50/60 p-6">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-brand-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 16v-4M12 8h.01" />
                        </svg>
                        <div class="text-xs leading-relaxed text-slate-600 sm:text-sm">
                            <p class="font-bold text-brand-teal">Before you book</p>
                            <p class="mt-1">{{ config('clinic.referral_notice') }}</p>
                            <p class="mt-2">
                                Referrals can be faxed to {{ config('clinic.fax') }}, sent electronically, or brought along on the day.
                                See <a href="{{ route('about') }}#referrals" class="font-semibold text-brand-teal underline decoration-emerald-300 underline-offset-2 hover:text-brand-accent">referral information</a> for details.
                            </p>
                        </div>
                    </div>
                </section>
            </div>

            {{-- Sidebar --}}
            <aside class="lg:col-span-4">
                <div class="space-y-6 lg:sticky lg:top-28">

                    {{-- At a glance --}}
                    <div class="rounded-2xl border border-slate-200/90 bg-white p-6 shadow-sm">
                        <h2 class="font-editorial text-lg font-bold text-brand-teal">At a glance</h2>

                        <dl class="mt-4 space-y-4 text-xs">
                            <div>
                                <dt class="font-semibold tracking-wider text-slate-400 uppercase">Department</dt>
                                <dd class="mt-1 font-semibold text-slate-800">{{ $specialist->specialty?->name ?? 'General' }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold tracking-wider text-slate-400 uppercase">Consulting days</dt>
                                <dd class="mt-1 font-semibold text-slate-800">{{ $specialist->consulting_days ?? 'Contact reception' }}</dd>
                            </div>
                            <div>
                                <dt class="font-semibold tracking-wider text-slate-400 uppercase">Where</dt>
                                <dd class="mt-1 font-semibold text-slate-800">
                                    {{ config('clinic.address.line1') }},<br>{{ config('clinic.address.line2') }}
                                </dd>
                            </div>
                            <div>
                                <dt class="font-semibold tracking-wider text-slate-400 uppercase">Reception</dt>
                                <dd class="mt-1">
                                    <a href="tel:{{ config('clinic.phone_link') }}" class="font-semibold text-slate-800 transition-colors hover:text-brand-teal">{{ config('clinic.phone') }}</a>
                                </dd>
                            </div>
                        </dl>

                        <a href="{{ config('clinic.directions_url') }}" target="_blank" rel="noopener noreferrer"
                           class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-lg border border-slate-300 px-4 py-2.5 text-xs font-bold text-brand-teal transition-colors hover:bg-slate-50">
                            Get directions
                        </a>
                    </div>

                    {{-- Booking CTA --}}
                    <div class="rounded-2xl bg-brand-teal p-6 text-white shadow-sm">
                        <h2 class="font-editorial text-lg font-bold">Request an appointment</h2>
                        <p class="mt-1.5 text-xs leading-relaxed text-teal-100">
                            Send your details through and our reception team will call you back to confirm a time with {{ $specialist->name }}.
                        </p>

                        <button type="button" @click="$dispatch('open-appointment-modal', @js($bookPayload))"
                                class="mt-4 w-full rounded-lg bg-white px-4 py-2.5 text-xs font-bold text-brand-teal transition-colors hover:bg-teal-50">
                            Request an appointment
                        </button>
                    </div>
                </div>
            </aside>
        </div>

        {{-- ========================================================= --}}
        {{-- Colleagues in the same department                           --}}
        {{-- ========================================================= --}}
        @if ($colleagues->isNotEmpty())
            <section class="mt-14 border-t border-slate-200 pt-10 lg:mt-20">
                <h2 class="{{ $heading }}">
                    More in {{ $specialist->specialty?->name }}
                </h2>

                <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($colleagues as $colleague)
                        <a href="{{ $colleague->profileUrl() }}"
                           class="group flex items-center gap-4 rounded-xl border border-slate-200/90 bg-white p-4 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md">
                            <div class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-full border border-slate-200 bg-slate-100">
                                @if ($colleague->image)
                                    <img src="{{ $colleague->image }}" alt="{{ $colleague->name }}"
                                         loading="lazy" class="h-full w-full object-cover object-top">
                                @endif
                            </div>

                            <div class="min-w-0">
                                <p class="truncate text-sm font-bold text-brand-teal transition-colors group-hover:text-brand-accent">{{ $colleague->name }}</p>
                                <p class="truncate text-[11px] text-slate-500">{{ $colleague->role }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
