@extends('layouts.admin')

@section('heading', 'Specialties')
@section('subheading', 'The clinical departments and care areas shown on the homepage')

@section('header-actions')
    <a href="{{ route('admin.specialties.create') }}"
       class="inline-flex items-center gap-1.5 rounded-lg bg-brand-teal px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-brand-dark">
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
            <path d="M12 5v14M5 12h14" />
        </svg>
        <span>Add specialty</span>
    </a>
@endsection

@section('content')
<div x-data="bulkSelection({ ids: @js($specialties->getCollection()->pluck('id')) })">

    <div class="mb-5 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-5">
        <div class="flex items-center gap-3">
            @unless ($specialties->isEmpty())
                <x-admin.select-checkbox label="Select all specialties on this page" />
            @endunless

            <p class="text-xs text-slate-500">
                {{ trans_choice(':count specialty configured|:count specialties configured', $specialties->total(), ['count' => $specialties->total()]) }}
            </p>
        </div>

        <form method="GET" action="{{ route('admin.specialties.index') }}" class="flex gap-2">
            <label for="specialty-search" class="sr-only">Search specialties</label>
            <div class="relative">
                <svg class="absolute top-2.5 left-3 h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7" />
                    <path d="m20 20-3.5-3.5" />
                </svg>
                <input id="specialty-search" type="search" name="q" value="{{ $search }}"
                       placeholder="Search specialties…"
                       class="w-full rounded-lg border border-slate-300 py-2 pr-3 pl-9 text-xs text-slate-800 transition focus:border-transparent focus:ring-2 focus:ring-brand-teal focus:outline-none sm:w-56">
            </div>

            <button type="submit"
                    class="rounded-lg bg-brand-teal px-4 py-2 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-brand-dark">
                Search
            </button>
        </form>
    </div>

    {{-- Bulk actions --}}
    <div class="mb-5 overflow-hidden rounded-2xl border border-brand-teal/20 bg-white shadow-sm">
        <x-admin.bulk-bar :action="route('admin.specialties.bulk')" label="specialty">
            <button type="submit" name="action" value="activate"
                    class="rounded-lg border border-emerald-300 bg-white px-3.5 py-1.5 text-xs font-semibold text-emerald-700 transition-colors hover:bg-emerald-50">
                Show on website
            </button>

            <button type="submit" name="action" value="deactivate"
                    class="rounded-lg border border-slate-300 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                Hide from website
            </button>

            <button type="submit" name="action" value="delete"
                    onclick="return confirm('Remove the selected specialties from the website? Their specialists and requests will be kept.')"
                    class="rounded-lg border border-red-300 bg-white px-3.5 py-1.5 text-xs font-semibold text-red-700 transition-colors hover:bg-red-50">
                Remove selected
            </button>
        </x-admin.bulk-bar>
    </div>

    @if ($specialties->isEmpty())
        <div class="rounded-2xl border border-slate-200 bg-white p-12 text-center shadow-sm">
            <p class="font-editorial text-lg font-bold text-brand-teal">No specialties found</p>
            <p class="mt-1 text-sm text-slate-500">
                @if ($search !== '')
                    Nothing matches “{{ $search }}”.
                @else
                    Add the departments your clinic offers to populate the homepage.
                @endif
            </p>

            <a href="{{ route('admin.specialties.create') }}"
               class="mt-4 inline-block rounded-lg bg-brand-teal px-4 py-2 text-xs font-semibold text-white hover:bg-brand-dark">
                Add specialty
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($specialties as $specialty)
                <div class="flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md"
                     :class="isSelected({{ $specialty->id }}) && 'border-brand-teal/40 ring-1 ring-brand-teal/30'">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <x-admin.select-checkbox :id="$specialty->id"
                                label="Select {{ $specialty->name }}" />

                            <span class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-xl bg-brand-teal/5 text-brand-accent">
                                <x-specialty-icon :name="$specialty->icon" class="h-8 w-8" />
                            </span>
                        </div>

                        @if ($specialty->is_active)
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-0.5 text-[11px] font-semibold text-emerald-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                Live
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-100 px-2.5 py-0.5 text-[11px] font-semibold text-slate-500">
                                <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                Hidden
                            </span>
                        @endif
                    </div>

                    <h2 class="mt-4 font-editorial text-xl font-bold text-brand-teal">{{ $specialty->name }}</h2>

                    <p class="mt-1.5 flex-grow text-xs leading-relaxed text-slate-600">
                        {{ $specialty->short_description }}
                    </p>

                    <div class="mt-4 flex flex-wrap gap-2 text-[11px]">
                        <span class="rounded-md border border-slate-200 bg-slate-50 px-2 py-1 font-semibold text-slate-600">
                            {{ trans_choice(':count specialist|:count specialists', $specialty->specialists_count, ['count' => $specialty->specialists_count]) }}
                        </span>
                        <span class="rounded-md border border-slate-200 bg-slate-50 px-2 py-1 font-semibold text-slate-600">
                            {{ count($specialty->conditions ?? []) }} conditions
                        </span>
                        <span class="rounded-md border border-slate-200 bg-slate-50 px-2 py-1 font-semibold text-slate-600">
                            Order {{ $specialty->sort_order }}
                        </span>
                    </div>

                    <div class="mt-5 flex items-center justify-between gap-3 border-t border-slate-100 pt-4">
                        <a href="{{ route('admin.specialties.edit', $specialty) }}"
                           class="text-xs font-semibold text-brand-teal hover:text-brand-accent">
                            Edit specialty
                        </a>

                        <form method="POST" action="{{ route('admin.specialties.destroy', $specialty) }}"
                              onsubmit="return confirm('Remove {{ $specialty->name }} from the website? Related specialists and requests will be kept.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="text-xs font-semibold text-slate-400 transition-colors hover:text-red-600">
                                Remove
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($specialties->hasPages())
            <div class="mt-5 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                {{ $specialties->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
