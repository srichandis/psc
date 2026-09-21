@if (session('success'))
    <div class="mb-6 flex animate-fade-in items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900">
        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="10" />
            <path d="m8 12 3 3 5-6" />
        </svg>
        <p>{{ session('success') }}</p>
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 flex animate-fade-in items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-900">
        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="10" />
            <path d="M12 8v4M12 16h.01" />
        </svg>
        <div>
            <p class="font-semibold">There {{ $errors->count() === 1 ? 'is 1 problem' : 'are '.$errors->count().' problems' }} with your submission.</p>
            <ul class="mt-1 list-inside list-disc space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
