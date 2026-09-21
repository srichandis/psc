@php
    // Re-open automatically when the patient returns after a failed validation
    // or a successful submission so the outcome is always visible.
    $shouldOpen = $errors->any() || session()->has('appointment_booked');

    $field = 'w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs text-slate-800 transition focus:border-transparent focus:ring-2 focus:ring-brand-teal focus:outline-none';
    $label = 'mb-1 block text-xs font-semibold text-slate-700';
@endphp

<div
    x-data="{ open: @js($shouldOpen) }"
    x-cloak
    x-effect="document.body.classList.toggle('overflow-hidden', open)"
    @keydown.escape.window="open = false"
    @open-appointment-modal.window="
        open = true;
        const detail = $event.detail || {};
        if (detail.specialistId) { $refs.specialist.value = detail.specialistId; }
        if (detail.specialty) { $refs.specialty.value = detail.specialty; }
    "
>
    {{-- Backdrop --}}
    <div x-show="open" x-cloak x-transition.opacity.duration.200ms
         @click="open = false"
         class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm"></div>

    {{-- Dialog --}}
    <div x-show="open" x-cloak
         class="pointer-events-none fixed inset-0 z-50 flex items-start justify-center overflow-y-auto p-4 sm:items-center sm:p-6"
         role="dialog" aria-modal="true" aria-labelledby="appointment-modal-title">

        <div class="pointer-events-auto relative w-full max-w-lg animate-scale-in overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-2xl">

            {{-- Header --}}
            <div class="flex items-center justify-between bg-brand-teal px-6 py-5 text-white">
                <div>
                    <h3 id="appointment-modal-title" class="font-editorial text-2xl font-bold">
                        {{ session()->has('appointment_booked') ? 'Request Received' : 'Request an Appointment' }}
                    </h3>
                    <p class="mt-0.5 text-xs text-teal-100">{{ config('clinic.name') }} &bull; Coomera QLD</p>
                </div>

                <button type="button" @click="open = false"
                        class="rounded-lg p-1 text-slate-300 transition-colors hover:bg-white/10 hover:text-white"
                        aria-label="Close dialog">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <path d="M6 6l12 12M18 6L6 18" />
                    </svg>
                </button>
            </div>

            <div class="p-6">
                @if (session()->has('appointment_booked'))
                    {{-- ------------------------------------------------ --}}
                    {{-- Success state                                      --}}
                    {{-- ------------------------------------------------ --}}
                    <div class="space-y-4 py-2 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-emerald-50 text-brand-accent ring-4 ring-emerald-50">
                            <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10" />
                                <path d="m8 12 3 3 5-6" />
                            </svg>
                        </div>

                        <div class="space-y-1">
                            <h4 class="font-editorial text-2xl font-bold text-brand-teal">Thank you{{ session('appointment_booked') ? ', '.session('appointment_booked') : '' }}</h4>
                            <p class="mx-auto max-w-sm text-xs leading-relaxed text-slate-600">
                                Your request has been sent to our reception team. We will review your referral details and
                                call you on the number provided to confirm your appointment time.
                            </p>
                        </div>

                        <div class="space-y-1.5 rounded-xl border border-slate-200/80 bg-slate-50 p-4 text-left text-xs">
                            <div class="flex justify-between gap-3">
                                <span class="text-slate-500">Location</span>
                                <span class="text-right font-semibold text-slate-800">{{ config('clinic.address.full') }}</span>
                            </div>
                            <div class="flex justify-between gap-3">
                                <span class="text-slate-500">Reception</span>
                                <span class="font-semibold text-slate-800">{{ config('clinic.phone') }}</span>
                            </div>
                            <div class="flex justify-between gap-3">
                                <span class="text-slate-500">Email</span>
                                <span class="break-all font-semibold text-slate-800">{{ config('clinic.email') }}</span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 pt-1 sm:flex-row sm:justify-center">
                            <a href="tel:{{ config('clinic.phone_link') }}"
                               class="rounded-lg border border-slate-200 px-5 py-2.5 text-xs font-semibold text-brand-teal transition-colors hover:bg-slate-50">
                                Call reception instead
                            </a>
                            <button type="button" @click="open = false"
                                    class="rounded-lg bg-brand-teal px-5 py-2.5 text-xs font-semibold text-white shadow-sm transition-all hover:bg-brand-dark">
                                Return to the website
                            </button>
                        </div>
                    </div>
                @else
                    {{-- ------------------------------------------------ --}}
                    {{-- Booking form                                       --}}
                    {{-- ------------------------------------------------ --}}
                    <form method="POST" action="{{ route('appointments.store') }}" class="space-y-4">
                        @csrf

                        <div class="flex items-start gap-2.5 rounded-lg border border-amber-200 bg-amber-50 p-3 text-xs text-amber-900">
                            <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 8v4M12 16h.01" />
                            </svg>
                            <p><strong class="font-semibold">Medicare note:</strong> {{ config('clinic.referral_notice') }}</p>
                        </div>

                        @if ($errors->any())
                            <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-xs text-red-800">
                                <p class="font-semibold">Please check the highlighted fields and try again.</p>
                                <ul class="mt-1 list-inside list-disc space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Patient name --}}
                        <div>
                            <label for="appointment-full-name" class="{{ $label }}">
                                Patient full name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <svg class="absolute top-2.5 left-3 h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                <input id="appointment-full-name" type="text" name="full_name" required
                                       value="{{ old('full_name') }}" placeholder="e.g. John Smith"
                                       class="{{ $field }} pl-9 @error('full_name') border-red-400 @enderror">
                            </div>
                            @error('full_name')
                                <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Phone + email --}}
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div>
                                <label for="appointment-phone" class="{{ $label }}">
                                    Contact phone <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <svg class="absolute top-2.5 left-3 h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M3 5a2 2 0 0 1 2-2h3.28a1 1 0 0 1 .948.684l1.498 4.493a1 1 0 0 1-.502 1.21l-2.257 1.13a11.042 11.042 0 0 0 5.516 5.516l1.13-2.257a1 1 0 0 1 1.21-.502l4.493 1.498a1 1 0 0 1 .684.949V19a2 2 0 0 1-2 2h-1C9.716 21 3 14.284 3 6z" />
                                    </svg>
                                    <input id="appointment-phone" type="tel" name="phone" required
                                           value="{{ old('phone') }}" placeholder="e.g. 0412 345 678"
                                           class="{{ $field }} pl-9 @error('phone') border-red-400 @enderror">
                                </div>
                                @error('phone')
                                    <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="appointment-email" class="{{ $label }}">Email address</label>
                                <div class="relative">
                                    <svg class="absolute top-2.5 left-3 h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect x="2" y="4" width="20" height="16" rx="2" />
                                        <path d="m22 7-10 6L2 7" />
                                    </svg>
                                    <input id="appointment-email" type="email" name="email"
                                           value="{{ old('email') }}" placeholder="john@example.com"
                                           class="{{ $field }} pl-9 @error('email') border-red-400 @enderror">
                                </div>
                                @error('email')
                                    <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Specialty + specialist --}}
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div>
                                <label for="appointment-specialty" class="{{ $label }}">
                                    Specialty <span class="text-red-500">*</span>
                                </label>
                                <select id="appointment-specialty" name="specialty" x-ref="specialty" required
                                        class="{{ $field }} bg-white @error('specialty') border-red-400 @enderror">
                                    <option value="">Choose a specialty…</option>
                                    @foreach ($bookingSpecialties as $specialty)
                                        <option value="{{ $specialty->name }}" @selected(old('specialty') === $specialty->name)>
                                            {{ $specialty->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('specialty')
                                    <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="appointment-specialist" class="{{ $label }}">Preferred specialist</label>
                                <select id="appointment-specialist" name="specialist_id" x-ref="specialist"
                                        class="{{ $field }} bg-white @error('specialist_id') border-red-400 @enderror">
                                    <option value="">Any available specialist</option>
                                    @foreach ($bookingSpecialists as $option)
                                        <option value="{{ $option->id }}" @selected((string) old('specialist_id') === (string) $option->id)>
                                            {{ $option->name }}@if ($option->specialty) — {{ $option->specialty->name }}@endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('specialist_id')
                                    <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Referral status --}}
                        <fieldset>
                            <legend class="{{ $label }}">Do you currently hold a GP referral?</legend>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach (\App\Models\Appointment::REFERRAL_STATUSES as $value => $optionLabel)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="referral_status" value="{{ $value }}" class="peer sr-only"
                                               @checked(old('referral_status', 'yes') === $value)>
                                        <span class="block rounded-lg border border-slate-200 px-2 py-2 text-center text-xs font-medium text-slate-600 transition-all hover:bg-slate-50 peer-checked:border-brand-teal peer-checked:bg-brand-teal/5 peer-checked:font-semibold peer-checked:text-brand-teal peer-focus-visible:ring-2 peer-focus-visible:ring-brand-teal">
                                            {{ $optionLabel }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                            @error('referral_status')
                                <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                            @enderror
                        </fieldset>

                        {{-- Preferred date + time --}}
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div>
                                <label for="appointment-date" class="{{ $label }}">Preferred date</label>
                                <input id="appointment-date" type="date" name="preferred_date"
                                       min="{{ now()->toDateString() }}"
                                       value="{{ old('preferred_date') }}"
                                       class="{{ $field }} @error('preferred_date') border-red-400 @enderror">
                                @error('preferred_date')
                                    <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="appointment-time" class="{{ $label }}">Preferred time</label>
                                <select id="appointment-time" name="preferred_time" class="{{ $field }} bg-white">
                                    @foreach ($bookingTimeWindows as $window)
                                        <option value="{{ $window }}" @selected(old('preferred_time') === $window)>{{ $window }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div>
                            <label for="appointment-notes" class="{{ $label }}">Referral details / clinical notes (optional)</label>
                            <textarea id="appointment-notes" name="notes" rows="2"
                                      placeholder="Referring doctor, relevant symptoms, or scheduling preferences…"
                                      class="{{ $field }} @error('notes') border-red-400 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="button" @click="open = false"
                                    class="rounded-lg px-4 py-2 text-xs font-semibold text-slate-600 transition-colors hover:bg-slate-100">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="rounded-lg bg-brand-teal px-5 py-2.5 text-xs font-semibold text-white shadow-sm transition-all hover:bg-brand-dark active:scale-95">
                                Submit Appointment Request
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
