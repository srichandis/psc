@props([
    'action',
    'label' => 'item',
])

@php
    // Kept simple and plural-safe: the labels passed in are single words.
    $plural = \Illuminate\Support\Str::plural($label);
@endphp

{{--
    Appears once rows are selected. The selected ids are mirrored into hidden
    inputs so they reach the server without wrapping the table in a form (the
    tables already contain per-row forms, and forms cannot be nested).
--}}
<div x-show="count > 0" x-cloak x-transition.opacity.duration.150ms
     class="border-b border-brand-teal/20 bg-brand-teal/5 px-4 py-3 sm:px-5">
    <form method="POST" action="{{ $action }}" class="flex flex-wrap items-center gap-x-3 gap-y-2">
        @csrf

        <template x-for="id in selected" :key="id">
            <input type="hidden" name="ids[]" :value="id">
        </template>

        <span class="inline-flex items-center gap-2 text-xs font-semibold text-brand-teal">
            <span class="flex h-6 min-w-6 items-center justify-center rounded-full bg-brand-teal px-1.5 text-[11px] font-bold text-white"
                  x-text="count"></span>
            <span x-text="count === 1 ? '{{ $label }} selected' : '{{ $plural }} selected'"></span>
        </span>

        <button type="button" @click="clear()"
                class="text-xs font-semibold text-slate-500 underline decoration-slate-300 underline-offset-2 transition-colors hover:text-slate-700">
            Clear
        </button>

        <span class="mx-1 hidden h-5 w-px bg-brand-teal/20 sm:block" aria-hidden="true"></span>

        {{ $slot }}
    </form>
</div>
