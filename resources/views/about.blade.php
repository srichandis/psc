@extends('layouts.app')

@section('title', 'About Us | '.config('clinic.name'))
@section('meta_description', 'Clinic location and parking, what to bring to your appointment, how to book, and referral information for '.config('clinic.name').'.')

@php
    $heading = 'font-editorial text-2xl font-bold text-brand-teal sm:text-3xl';
    $prose = 'space-y-4 text-sm leading-relaxed text-slate-600 sm:text-base';
    $iconWrap = 'flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-brand-teal/10 text-brand-teal';
    $bullet = 'flex items-start gap-3 text-sm text-slate-700 sm:text-base';
@endphp

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-14 lg:px-8">

        {{-- Page header --}}
        <header class="max-w-3xl">
            <span class="block text-xs font-bold tracking-[0.2em] text-brand-accent uppercase">About us</span>
            <h1 class="mt-2 font-editorial text-4xl font-bold tracking-tight text-brand-teal sm:text-5xl">
                About Our Clinic
            </h1>
            <p class="mt-6 rounded-2xl border border-l-4 border-slate-200/90 border-l-brand-accent bg-white px-6 py-5 text-base leading-relaxed font-semibold text-brand-teal shadow-sm">
                Specialist care with a personal approach, delivered by a multidisciplinary team in the heart of Coomera.
            </p>
        </header>

        <div class="mt-6 grid grid-cols-1 gap-10 lg:mt-8 lg:grid-cols-12 lg:gap-12">

            {{-- ================================================== --}}
            {{-- Main content column                                --}}
            {{-- ================================================== --}}
            <div class="space-y-12 lg:col-span-8">

                {{-- Introduction --}}
                <section>
                    <div class="{{ $prose }}">
                        <p>{{ config('clinic.name') }} is a well-established multidisciplinary specialist medical clinic located in Coomera, in the heart of the rapidly growing Northern Gold Coast.</p>
                        <p>Our vision has always been simple: to make high-quality specialist healthcare more accessible to patients and families within our local community, without the need to travel long distances to access experienced specialist care.</p>
                        <p>Located at Suite 1, {{ config('clinic.address.full') }}, our clinic brings together experienced medical specialists and allied health professionals across a range of disciplines. Our current services include Psychiatry, Neurology, Endocrinology, Nephrology, Geriatric Medicine and Psychology, with our specialist services continuing to evolve in response to the healthcare needs of the community.</p>
                        <p>At {{ config('clinic.name') }}, we believe good healthcare begins with listening. Every patient comes with their own history, concerns and circumstances, and our specialists are committed to providing thoughtful, evidence-based and individualised care.</p>
                        <p>Having multiple specialties within one clinic also allows patients to access a broader range of expertise in a familiar and convenient setting. Our visiting specialists maintain their own areas of clinical expertise while being supported by an experienced and welcoming administrative team.</p>
                    </div>
                </section>

                {{-- Our philosophy --}}
                <section id="philosophy" class="scroll-mt-24">
                    <div class="flex items-center gap-3">
                        <span class="{{ $iconWrap }}">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                            </svg>
                        </span>
                        <h2 class="{{ $heading }}">Our philosophy</h2>
                    </div>

                    <p class="mt-5 font-editorial text-xl leading-snug font-bold text-brand-teal sm:text-2xl">
                        Specialist expertise. Personalised care. Close to home.
                    </p>

                    <div class="{{ $prose }} mt-4">
                        <p>We aim to provide specialist healthcare that combines clinical excellence with compassion, respect and a genuine understanding of the individual behind the diagnosis.</p>
                        <p>Whether you are attending for an initial specialist assessment, ongoing treatment or multidisciplinary care, our team is here to make your healthcare journey as straightforward and supportive as possible.</p>
                    </div>
                </section>

                {{-- Location and parking --}}
                <section id="location" class="scroll-mt-24">
                    <div class="flex items-center gap-3">
                        <span class="{{ $iconWrap }}">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </span>
                        <h2 class="{{ $heading }}">Location and parking</h2>
                    </div>

                    <div class="{{ $prose }} mt-5">
                        <p>Our clinic is conveniently located at {{ config('clinic.address.full') }}.</p>
                        <p>{{ config('clinic.parking') }}</p>
                    </div>

                    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200/90 shadow-sm">
                        <img src="/images/clinic.png"
                             alt="{{ config('clinic.name') }} at {{ config('clinic.address.full') }}"
                             loading="lazy" decoding="async"
                             class="h-56 w-full object-cover object-center sm:h-72">
                    </div>

                    <a href="{{ config('clinic.directions_url') }}" target="_blank" rel="noopener noreferrer"
                       class="mt-5 inline-flex items-center gap-2 rounded-md bg-brand-teal px-5 py-3 text-xs font-bold text-white shadow-sm transition-all hover:bg-brand-dark active:scale-[0.98]">
                        <span>Get directions</span>
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </a>
                </section>

                {{-- Before your visit --}}
                <section>
                    <div class="flex items-center gap-3">
                        <span class="{{ $iconWrap }}">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <path d="M14 2v6h6" />
                                <path d="M9 13h6M9 17h4" />
                            </svg>
                        </span>
                        <h2 class="{{ $heading }}">Before your visit</h2>
                    </div>

                    <div class="{{ $prose }} mt-5">
                        <p>Our specialists strive to see all patients on time; however, please understand that some consultations may take longer than expected.</p>
                        <p>Upon arrival, you&rsquo;ll be asked to complete some essential patient forms. To help us streamline your visit, please remember to bring:</p>
                    </div>

                    <ul class="mt-5 space-y-3">
                        @foreach (['Your Medicare Card', 'Your referral, if not already provided'] as $item)
                            <li class="{{ $bullet }}">
                                <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-brand-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="m8 12 3 3 5-6" />
                                </svg>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>

                {{-- Booking an appointment --}}
                <section id="booking" class="scroll-mt-24">
                    <div class="flex items-center gap-3">
                        <span class="{{ $iconWrap }}">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 5a2 2 0 0 1 2-2h3.28a1 1 0 0 1 .948.684l1.498 4.493a1 1 0 0 1-.502 1.21l-2.257 1.13a11.042 11.042 0 0 0 5.516 5.516l1.13-2.257a1 1 0 0 1 1.21-.502l4.493 1.498a1 1 0 0 1 .684.949V19a2 2 0 0 1-2 2h-1C9.716 21 3 14.284 3 6z" />
                            </svg>
                        </span>
                        <h2 class="{{ $heading }}">Booking an appointment</h2>
                    </div>

                    <div class="{{ $prose }} mt-5">
                        <p>To make an appointment, please call our friendly reception team on {{ config('clinic.phone') }}.</p>
                        <p>They&rsquo;ll assist you in finding a suitable time and guide you through the booking process.</p>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <button type="button" @click="$dispatch('open-appointment-modal')"
                                class="inline-flex items-center gap-2 rounded-md bg-brand-teal px-5 py-3 text-xs font-bold text-white shadow-sm transition-all hover:bg-brand-dark active:scale-[0.98]">
                            <span>Request an appointment online</span>
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14M13 6l6 6-6 6" />
                            </svg>
                        </button>

                        <a href="tel:{{ config('clinic.phone_link') }}"
                           class="inline-flex items-center gap-2 rounded-md border border-brand-teal/40 bg-white px-5 py-3 text-xs font-bold text-brand-teal transition-colors hover:bg-slate-50">
                            Call {{ config('clinic.phone') }}
                        </a>
                    </div>
                </section>

                {{-- Referrals --}}
                <section id="referrals" class="scroll-mt-24">
                    <div class="flex items-center gap-3">
                        <span class="{{ $iconWrap }}">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <path d="M14 2v6h6" />
                                <path d="m9 15 2 2 4-4" />
                            </svg>
                        </span>
                        <h2 class="{{ $heading }}">Referrals</h2>
                    </div>

                    <div class="{{ $prose }} mt-5">
                        <p>A referral from your GP or another specialist is required to see one of our specialists. Referrals can be:</p>
                    </div>

                    <ul class="mt-5 space-y-3">
                        @foreach (['Faxed', 'Sent electronically', 'Or brought with you on the day of your appointment'] as $item)
                            <li class="{{ $bullet }}">
                                <span class="mt-2 h-1.5 w-1.5 flex-shrink-0 rounded-full bg-brand-accent" aria-hidden="true"></span>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>
            </div>

            {{-- ================================================== --}}
            {{-- Sidebar                                            --}}
            {{-- ================================================== --}}
            <aside class="lg:col-span-4">
                <div class="space-y-6 lg:sticky lg:top-28">

                    {{-- Contact details --}}
                    <div class="rounded-2xl border border-slate-200/90 bg-white p-6 shadow-sm">
                        <h3 class="font-editorial text-lg font-bold text-brand-teal">Visit us</h3>

                        <ul class="mt-4 space-y-4 text-xs text-slate-600">
                            <li class="flex items-start gap-3">
                                <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-brand-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span class="font-semibold text-slate-800">{{ config('clinic.address.line1') }},<br>{{ config('clinic.address.line2') }}</span>
                            </li>

                            <li class="flex items-center gap-3">
                                <svg class="h-4 w-4 flex-shrink-0 text-brand-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 5a2 2 0 0 1 2-2h3.28a1 1 0 0 1 .948.684l1.498 4.493a1 1 0 0 1-.502 1.21l-2.257 1.13a11.042 11.042 0 0 0 5.516 5.516l1.13-2.257a1 1 0 0 1 1.21-.502l4.493 1.498a1 1 0 0 1 .684.949V19a2 2 0 0 1-2 2h-1C9.716 21 3 14.284 3 6z" />
                                </svg>
                                <a href="tel:{{ config('clinic.phone_link') }}" class="font-semibold text-slate-800 hover:text-brand-teal">{{ config('clinic.phone') }}</a>
                            </li>

                            <li class="flex items-center gap-3">
                                <svg class="h-4 w-4 flex-shrink-0 text-brand-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M6 9V4h12v5M6 18H4a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-2" />
                                    <rect x="6" y="14" width="12" height="8" rx="1" />
                                </svg>
                                <span>Fax {{ config('clinic.fax') }}</span>
                            </li>

                            <li class="flex items-start gap-3">
                                <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-brand-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="2" y="4" width="20" height="16" rx="2" />
                                    <path d="m22 7-10 6L2 7" />
                                </svg>
                                <a href="mailto:{{ config('clinic.email') }}" class="break-all hover:text-brand-teal">{{ config('clinic.email') }}</a>
                            </li>
                        </ul>
                    </div>

                    {{-- Opening hours --}}
                    <div class="rounded-2xl border border-slate-200/90 bg-white p-6 shadow-sm">
                        <h3 class="font-editorial text-lg font-bold text-brand-teal">Opening hours</h3>

                        <dl class="mt-4 space-y-2.5 text-xs">
                            @foreach (config('clinic.hours') as $entry)
                                <div class="flex justify-between gap-3">
                                    <dt class="text-slate-500">{{ $entry['days'] }}</dt>
                                    <dd class="font-semibold text-slate-800">{{ $entry['times'] }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>

                    {{-- Booking call to action --}}
                    <div class="rounded-2xl bg-brand-teal p-6 text-white shadow-sm">
                        <h3 class="font-editorial text-lg font-bold">Ready to book?</h3>
                        <p class="mt-1.5 text-xs leading-relaxed text-teal-100">
                            Send your referral details through and our reception team will call you back to confirm a time.
                        </p>

                        <button type="button" @click="$dispatch('open-appointment-modal')"
                                class="mt-4 w-full rounded-lg bg-white px-4 py-2.5 text-xs font-bold text-brand-teal transition-colors hover:bg-teal-50">
                            Request an appointment
                        </button>
                    </div>
                </div>
            </aside>
        </div>

        {{-- ====================================================== --}}
        {{-- Questions and urgent appointments                       --}}
        {{-- ====================================================== --}}
        <section id="contact" class="mt-14 scroll-mt-24 rounded-2xl border border-slate-200/90 bg-white p-7 shadow-sm sm:p-10 lg:mt-20">
            <div class="max-w-3xl">
                <span class="block text-xs font-bold tracking-[0.2em] text-brand-accent uppercase">Contact</span>
                <h2 class="mt-2 font-editorial text-2xl font-bold text-brand-teal sm:text-3xl">
                    Questions before booking?
                </h2>

                <div class="{{ $prose }} mt-5">
                    <p>If you have any questions before booking, please contact us via our online form or give us a call.</p>
                    <p>Please note that appointment availability and waiting times vary depending on the specialist.</p>
                    <p>For urgent appointments, your referring doctor can arrange for you to be seen as soon as possible by contacting our clinic.</p>
                </div>

                <div class="mt-7 flex flex-wrap gap-3">
                    <button type="button" @click="$dispatch('open-appointment-modal')"
                            class="inline-flex items-center gap-2 rounded-md bg-brand-teal px-5 py-3 text-xs font-bold text-white shadow-sm transition-all hover:bg-brand-dark active:scale-[0.98]">
                        <span>Send an online enquiry</span>
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </button>

                    <a href="tel:{{ config('clinic.phone_link') }}"
                       class="inline-flex items-center gap-2 rounded-md border border-slate-300 bg-white px-5 py-3 text-xs font-bold text-brand-teal transition-colors hover:bg-slate-50">
                        Call {{ config('clinic.phone') }}
                    </a>

                    <a href="{{ config('clinic.directions_url') }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 rounded-md border border-slate-300 bg-white px-5 py-3 text-xs font-bold text-brand-teal transition-colors hover:bg-slate-50">
                        Get directions
                    </a>
                </div>

                <dl class="mt-8 grid grid-cols-1 gap-5 border-t border-slate-100 pt-6 text-xs sm:grid-cols-3">
                    <div>
                        <dt class="font-semibold tracking-wider text-slate-400 uppercase">Reception</dt>
                        <dd class="mt-1 font-semibold text-slate-800">{{ config('clinic.phone') }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold tracking-wider text-slate-400 uppercase">Fax</dt>
                        <dd class="mt-1 font-semibold text-slate-800">{{ config('clinic.fax') }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold tracking-wider text-slate-400 uppercase">Email</dt>
                        <dd class="mt-1 break-all font-semibold text-slate-800">{{ config('clinic.email') }}</dd>
                    </div>
                </dl>
            </div>
        </section>
    </div>
@endsection
