@extends('layouts.admin')

@section('heading', 'Add specialty')
@section('subheading', 'Create a new clinical department or care area')

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
    <div class="max-w-3xl">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            @include('admin.specialties._form', [
                'specialty' => $specialty,
                'action' => route('admin.specialties.store'),
                'method' => 'POST',
                'submitLabel' => 'Add specialty',
            ])
        </div>
    </div>
@endsection
