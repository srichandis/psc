@extends('layouts.admin')

@section('heading', 'Edit specialist')
@section('subheading', $specialist->name)

@section('header-actions')
    <a href="{{ route('admin.specialists.index') }}"
       class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 shadow-sm transition-colors hover:bg-slate-50">
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M19 12H5M11 6l-6 6 6 6" />
        </svg>
        <span>Back to specialists</span>
    </a>
@endsection

@section('content')
    <div class="max-w-3xl space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            @include('admin.specialists._form', [
                'specialist' => $specialist,
                'specialties' => $specialties,
                'action' => route('admin.specialists.update', $specialist),
                'method' => 'PUT',
                'submitLabel' => 'Save changes',
            ])
        </div>

        <div class="flex items-center justify-between gap-4 rounded-2xl border border-red-100 bg-red-50/40 p-6">
            <div>
                <h3 class="font-editorial text-base font-bold text-red-800">Remove from website</h3>
                <p class="mt-1 text-xs leading-relaxed text-red-700">
                    Their appointment requests are preserved so booking history is not lost.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.specialists.destroy', $specialist) }}"
                  onsubmit="return confirm('Remove {{ $specialist->name }} from the website?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="whitespace-nowrap rounded-lg border border-red-300 bg-white px-4 py-2.5 text-xs font-semibold text-red-700 transition-colors hover:bg-red-100">
                    Remove specialist
                </button>
            </form>
        </div>
    </div>
@endsection
