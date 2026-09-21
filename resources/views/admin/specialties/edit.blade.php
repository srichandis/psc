@extends('layouts.admin')

@section('heading', 'Edit specialty')
@section('subheading', $specialty->name)

@section('header-actions')
    <a href="{{ route('admin.specialties.index') }}"
       class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 shadow-sm transition-colors hover:bg-slate-50">
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M19 12H5M11 6l-6 6 6 6" />
        </svg>
        <span>Back to specialties</span>
    </a>
@endsection

@section('content')
    <div class="max-w-3xl space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            @include('admin.specialties._form', [
                'specialty' => $specialty,
                'action' => route('admin.specialties.update', $specialty),
                'method' => 'PUT',
                'submitLabel' => 'Save changes',
            ])
        </div>

        <div class="flex items-center justify-between gap-4 rounded-2xl border border-red-100 bg-red-50/40 p-6">
            <div>
                <h3 class="font-editorial text-base font-bold text-red-800">Remove from website</h3>
                <p class="mt-1 text-xs leading-relaxed text-red-700">
                    Specialists assigned here become unassigned. Appointment requests keep the specialty name they were
                    submitted with.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.specialties.destroy', $specialty) }}"
                  onsubmit="return confirm('Remove {{ $specialty->name }} from the website?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="whitespace-nowrap rounded-lg border border-red-300 bg-white px-4 py-2.5 text-xs font-semibold text-red-700 transition-colors hover:bg-red-100">
                    Remove specialty
                </button>
            </form>
        </div>
    </div>
@endsection
