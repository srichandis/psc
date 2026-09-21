@php $home = route('home', absolute: false); @endphp

<footer class="mt-20 bg-brand-teal pt-16 pb-12 text-slate-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 gap-10 border-b border-slate-700/60 pb-14 md:grid-cols-2 lg:grid-cols-4 lg:gap-12">

            {{-- Brand --}}
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    {{-- Light ink variant, for use on the dark footer. --}}
                    <img src="/images/logo-light.png" alt=""
                         class="h-10 w-10 flex-shrink-0 object-contain">
                    <div>
                        <span class="block font-editorial text-2xl leading-none font-bold tracking-tight text-white">{{ config('clinic.short_name') }}</span>
                        <span class="mt-0.5 block text-[10px] font-semibold tracking-[0.22em] text-teal-200 uppercase">Specialist Clinic</span>
                    </div>
                </div>

                <p class="pt-2 pr-4 text-xs leading-relaxed text-slate-300">
                    Specialist care across psychiatry, neurology, geriatric medicine, nephrology, endocrinology and psychology.
                </p>
            </div>

            {{-- Quick links --}}
            <div>
                <h4 class="mb-4 text-sm font-semibold tracking-wider text-white uppercase">Quick Links</h4>
                <ul class="space-y-2.5 text-xs text-slate-300">
                    <li><a href="{{ $home }}#home" class="transition-colors hover:text-teal-200">Home</a></li>
                    <li><a href="{{ $home }}#specialists" class="transition-colors hover:text-teal-200">Our Specialists</a></li>
                    <li><a href="{{ $home }}#specialties" class="transition-colors hover:text-teal-200">Specialties</a></li>
                    <li><a href="{{ $home }}#visit-process" class="transition-colors hover:text-teal-200">For Patients</a></li>
                    <li><a href="{{ route('about') }}" class="transition-colors hover:text-teal-200">About Us</a></li>
                    <li><a href="{{ route('about') }}#contact" class="transition-colors hover:text-teal-200">Contact</a></li>
                </ul>
            </div>

            {{-- Patient info --}}
            <div>
                <h4 class="mb-4 text-sm font-semibold tracking-wider text-white uppercase">Patient Information</h4>
                <ul class="space-y-2.5 text-xs text-slate-300">
                    <li>
                        <button type="button" @click="$dispatch('open-appointment-modal')" class="text-left transition-colors hover:text-teal-200">
                            Appointments
                        </button>
                    </li>
                    <li><a href="{{ route('about') }}#referrals" class="transition-colors hover:text-teal-200">Referral Information</a></li>
                    <li><a href="{{ route('about') }}#location" class="transition-colors hover:text-teal-200">Parking</a></li>
                    <li><a href="{{ route('about') }}#contact" class="transition-colors hover:text-teal-200">FAQs</a></li>
                    <li><span class="text-slate-400">Privacy Policy</span></li>
                </ul>

                <h4 class="mt-6 mb-4 text-sm font-semibold tracking-wider text-white uppercase">Opening Hours</h4>
                <ul class="space-y-1.5 text-xs text-slate-300">
                    @foreach (config('clinic.hours') as $entry)
                        <li class="flex justify-between gap-3">
                            <span>{{ $entry['days'] }}</span>
                            <span class="text-slate-400">{{ $entry['times'] }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="mb-4 text-sm font-semibold tracking-wider text-white uppercase">Get In Touch</h4>
                <ul class="space-y-3.5 text-xs text-slate-300">
                    <li class="flex items-start gap-3">
                        <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-teal-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ config('clinic.address.line1') }},<br>{{ config('clinic.address.line2') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="h-4 w-4 flex-shrink-0 text-teal-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 5a2 2 0 0 1 2-2h3.28a1 1 0 0 1 .948.684l1.498 4.493a1 1 0 0 1-.502 1.21l-2.257 1.13a11.042 11.042 0 0 0 5.516 5.516l1.13-2.257a1 1 0 0 1 1.21-.502l4.493 1.498a1 1 0 0 1 .684.949V19a2 2 0 0 1-2 2h-1C9.716 21 3 14.284 3 6z" />
                        </svg>
                        <a href="tel:{{ config('clinic.phone_link') }}" class="transition-colors hover:text-white">{{ config('clinic.phone') }}</a>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="h-4 w-4 flex-shrink-0 text-teal-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="2" y="4" width="20" height="16" rx="2" />
                            <path d="m22 7-10 6L2 7" />
                        </svg>
                        <a href="mailto:{{ config('clinic.email') }}" class="break-all transition-colors hover:text-white">{{ config('clinic.email') }}</a>
                    </li>
                    <li class="flex items-center gap-3 text-slate-400">
                        <span class="text-[11px] font-semibold tracking-wider uppercase">Fax</span>
                        <span>{{ config('clinic.fax') }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="flex flex-col items-center justify-between gap-4 pt-8 text-xs text-slate-400 sm:flex-row">
            <p>&copy; {{ now()->year }} {{ config('clinic.name') }}. All rights reserved.</p>
            <p class="font-script text-xl tracking-wide text-teal-100 sm:text-2xl">{{ config('clinic.script_signature') }}</p>
        </div>
    </div>
</footer>
