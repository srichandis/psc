@props(['specialty'])

<div x-data="{ open: false }" class="flex">
    {{-- Card --}}
    <button type="button" @click="open = true"
            class="group flex w-full flex-col justify-between rounded-xl border border-slate-200/90 bg-white p-6 text-center shadow-sm transition-all duration-200 hover:-translate-y-1 hover:shadow-md">
        <div>
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center text-brand-accent transition-all group-hover:scale-105 group-hover:text-brand-teal">
                <x-specialty-icon :name="$specialty->icon" />
            </div>

            <h3 class="mb-2 font-editorial text-lg font-bold text-brand-teal transition-colors group-hover:text-brand-accent sm:text-xl">
                {{ $specialty->name }}
            </h3>

            <p class="min-h-[54px] text-xs leading-relaxed text-slate-600">
                {{ $specialty->short_description }}
            </p>
        </div>

        <div class="mt-4 border-t border-slate-100 pt-5">
            <span class="inline-flex items-center gap-1 text-xs font-bold text-brand-teal transition-colors group-hover:text-brand-accent">
                <span>Learn More</span>
                <svg class="h-3.5 w-3.5 transition-transform group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12h14M13 6l6 6-6 6" />
                </svg>
            </span>
        </div>
    </button>

    {{-- Detail modal --}}
    <template x-teleport="body">
        <div x-show="open" x-cloak
             x-effect="document.body.classList.toggle('overflow-hidden', open)"
             @keydown.escape.window="open = false"
             class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto p-4 sm:items-center sm:p-6"
             role="dialog" aria-modal="true">

            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>

            <div class="relative w-full max-w-2xl animate-scale-in overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-2xl">

                <div class="relative bg-brand-teal p-6 text-white">
                    <button type="button" @click="open = false"
                            class="absolute top-4 right-4 rounded-lg p-1 text-slate-300 transition-colors hover:bg-white/10 hover:text-white"
                            aria-label="Close specialty detail">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                            <path d="M6 6l12 12M18 6L6 18" />
                        </svg>
                    </button>

                    <span class="mb-1 block text-[10px] font-bold tracking-widest text-teal-200 uppercase">Clinical specialty</span>
                    <h3 class="font-editorial text-2xl font-bold sm:text-3xl">{{ $specialty->name }}</h3>
                    <p class="mt-1 max-w-xl text-xs leading-relaxed text-teal-100">{{ $specialty->short_description }}</p>
                </div>

                <div class="space-y-6 p-6 text-xs text-slate-700 sm:p-8 sm:text-sm">
                    @if ($specialty->full_description)
                        <div class="space-y-2">
                            <h4 class="text-xs font-bold tracking-wider text-slate-400 uppercase">Department overview</h4>
                            <p class="leading-relaxed text-slate-600">{{ $specialty->full_description }}</p>
                        </div>
                    @endif

                    @if (! empty($specialty->conditions))
                        <div class="space-y-2">
                            <h4 class="text-xs font-bold tracking-wider text-slate-400 uppercase">Key conditions managed</h4>
                            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                                @foreach ($specialty->conditions as $condition)
                                    <div class="flex items-start gap-2 text-xs text-slate-700">
                                        <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-brand-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <circle cx="12" cy="12" r="10" />
                                            <path d="m8 12 3 3 5-6" />
                                        </svg>
                                        <span>{{ $condition }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (! empty($specialty->diagnostic_services))
                        <div class="space-y-2">
                            <h4 class="text-xs font-bold tracking-wider text-slate-400 uppercase">Diagnostic &amp; clinical services</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($specialty->diagnostic_services as $service)
                                    <span class="rounded-md border border-slate-200 bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">
                                        {{ $service }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($specialty->specialists->isNotEmpty())
                        <div class="space-y-2">
                            <h4 class="text-xs font-bold tracking-wider text-slate-400 uppercase">Department specialists</h4>
                            <div class="space-y-2">
                                @foreach ($specialty->specialists as $specialist)
                                    <a href="{{ $specialist->profileUrl() }}"
                                       class="group flex items-center justify-between gap-3 rounded-xl border border-slate-200/80 bg-slate-50 p-3 transition-colors hover:border-brand-teal/30 hover:bg-white">
                                        <div class="flex items-center gap-3">
                                            @if ($specialist->image)
                                                <img src="{{ $specialist->image }}" alt="{{ $specialist->name }}"
                                                     class="h-10 w-10 rounded-full border border-slate-200 object-cover">
                                            @endif
                                            <div>
                                                <p class="text-xs font-bold text-brand-teal group-hover:text-brand-accent">{{ $specialist->name }}</p>
                                                <p class="text-[11px] text-slate-500">{{ $specialist->role }}</p>
                                            </div>
                                        </div>
                                        <span class="whitespace-nowrap text-[11px] font-semibold text-brand-accent">
                                            {{ $specialist->consulting_days }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-2">
                        <button type="button" @click="open = false"
                                class="rounded-lg px-4 py-2 text-xs font-semibold text-slate-600 transition-colors hover:bg-slate-100">
                            Close
                        </button>

                        <button type="button"
                                @click="open = false; $dispatch('open-appointment-modal', { specialty: @js($specialty->name) })"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-brand-teal px-5 py-2.5 text-xs font-semibold text-white shadow-sm transition-all hover:bg-brand-dark">
                            <span>Request Consultation</span>
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 12h14M13 6l6 6-6 6" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
