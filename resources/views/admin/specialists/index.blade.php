@extends('layouts.admin')

@section('heading', 'Specialists')
@section('subheading', 'The doctors and clinicians shown in the website directory')

@section('header-actions')
    <a href="{{ route('admin.specialists.create') }}"
       class="inline-flex items-center gap-1.5 rounded-lg bg-brand-teal px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-brand-dark">
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
            <path d="M12 5v14M5 12h14" />
        </svg>
        <span>Add specialist</span>
    </a>
@endsection

@section('content')
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
         x-data="bulkSelection({ ids: @js($specialists->getCollection()->pluck('id')) })">
        <div class="flex flex-col gap-3 border-b border-slate-100 p-4 sm:flex-row sm:items-center sm:justify-between sm:p-5">
            <p class="text-xs text-slate-500">
                {{ trans_choice(':count specialist on the website|:count specialists on the website', $specialists->total(), ['count' => $specialists->total()]) }}
            </p>

            <form method="GET" action="{{ route('admin.specialists.index') }}" class="flex gap-2">
                <label for="specialist-search" class="sr-only">Search specialists</label>
                <div class="relative">
                    <svg class="absolute top-2.5 left-3 h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7" />
                        <path d="m20 20-3.5-3.5" />
                    </svg>
                    <input id="specialist-search" type="search" name="q" value="{{ $search }}"
                           placeholder="Search by name or role…"
                           class="w-full rounded-lg border border-slate-300 py-2 pr-3 pl-9 text-xs text-slate-800 transition focus:border-transparent focus:ring-2 focus:ring-brand-teal focus:outline-none sm:w-64">
                </div>

                <button type="submit"
                        class="rounded-lg bg-brand-teal px-4 py-2 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-brand-dark">
                    Search
                </button>
            </form>
        </div>

        {{-- Bulk actions --}}
        <x-admin.bulk-bar :action="route('admin.specialists.bulk')" label="specialist">
            <button type="submit" name="action" value="activate"
                    class="rounded-lg border border-emerald-300 bg-white px-3.5 py-1.5 text-xs font-semibold text-emerald-700 transition-colors hover:bg-emerald-50">
                Show on website
            </button>

            <button type="submit" name="action" value="deactivate"
                    class="rounded-lg border border-slate-300 bg-white px-3.5 py-1.5 text-xs font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                Hide from website
            </button>

            <button type="submit" name="action" value="delete"
                    onclick="return confirm('Remove the selected specialists from the website? Their appointment requests will be kept.')"
                    class="rounded-lg border border-red-300 bg-white px-3.5 py-1.5 text-xs font-semibold text-red-700 transition-colors hover:bg-red-50">
                Remove selected
            </button>
        </x-admin.bulk-bar>

        @if ($specialists->isEmpty())
            <div class="p-12 text-center">
                <p class="font-editorial text-lg font-bold text-brand-teal">No specialists found</p>
                <p class="mt-1 text-sm text-slate-500">
                    @if ($search !== '')
                        Nothing matches “{{ $search }}”.
                    @else
                        Add your first specialist to populate the website directory.
                    @endif
                </p>

                <a href="{{ route('admin.specialists.create') }}"
                   class="mt-4 inline-block rounded-lg bg-brand-teal px-4 py-2 text-xs font-semibold text-white hover:bg-brand-dark">
                    Add specialist
                </a>
            </div>
        @else
            <div class="scrollbar-thin overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">
                        <tr>
                            <th scope="col" class="w-12 px-5 py-3">
                                <x-admin.select-checkbox label="Select all specialists on this page" />
                            </th>
                            <th scope="col" class="px-5 py-3">Specialist</th>
                            <th scope="col" class="px-5 py-3">Specialty</th>
                            <th scope="col" class="px-5 py-3">Consulting days</th>
                            <th scope="col" class="px-5 py-3">Order</th>
                            <th scope="col" class="px-5 py-3">Visibility</th>
                            <th scope="col" class="px-5 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @foreach ($specialists as $specialist)
                            <tr class="transition-colors hover:bg-slate-50/70"
                                :class="isSelected({{ $specialist->id }}) && 'bg-brand-teal/5'">
                                <td class="px-5 py-4">
                                    <x-admin.select-checkbox :id="$specialist->id"
                                        label="Select {{ $specialist->name }}" />
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-11 w-11 flex-shrink-0 overflow-hidden rounded-full border border-slate-200 bg-slate-100">
                                            @if ($specialist->image)
                                                <img src="{{ $specialist->image }}" alt="" class="h-full w-full object-cover">
                                            @endif
                                        </div>

                                        <div class="min-w-0">
                                            <a href="{{ route('admin.specialists.edit', $specialist) }}"
                                               class="font-semibold text-slate-800 hover:text-brand-teal">
                                                {{ $specialist->name }}
                                            </a>
                                            <p class="truncate text-xs text-slate-500">{{ $specialist->role }}</p>
                                            <p class="truncate text-[11px] text-slate-400">{{ $specialist->qualifications }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    @if ($specialist->specialty)
                                        <span class="inline-flex rounded-md border border-slate-200 bg-slate-50 px-2 py-0.5 text-[11px] font-semibold text-slate-600">
                                            {{ $specialist->specialty->name }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">Unassigned</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-xs text-slate-600">
                                    {{ $specialist->consulting_days ?? '—' }}
                                </td>

                                <td class="px-5 py-4 text-xs text-slate-500">
                                    {{ $specialist->sort_order }}
                                </td>

                                <td class="px-5 py-4">
                                    @if ($specialist->is_active)
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
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.specialists.edit', $specialist) }}"
                                           class="text-xs font-semibold whitespace-nowrap text-brand-teal hover:text-brand-accent">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('admin.specialists.destroy', $specialist) }}"
                                              onsubmit="return confirm('Remove {{ $specialist->name }} from the website? Their appointment requests will be kept.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-xs font-semibold whitespace-nowrap text-slate-400 transition-colors hover:text-red-600">
                                                Remove
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($specialists->hasPages())
                <div class="border-t border-slate-100 p-4">
                    {{ $specialists->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
