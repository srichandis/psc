@extends('layouts.admin')

@section('heading', 'Booking request')
@section('subheading', 'Received '.$appointment->created_at->format('j F Y \a\t g:ia'))

@section('header-actions')
    <a href="{{ route('admin.appointments.index') }}"
       class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 shadow-sm transition-colors hover:bg-slate-50">
        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M19 12H5M11 6l-6 6 6 6" />
        </svg>
        <span>All requests</span>
    </a>
@endsection

@section('content')
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- Patient & request details --}}
        <div class="space-y-6 lg:col-span-2">

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h2 class="font-editorial text-2xl font-bold text-brand-teal">{{ $appointment->full_name }}</h2>
                        <p class="mt-0.5 text-xs text-slate-500">Request #{{ str_pad((string) $appointment->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>

                    <x-admin.status-badge :status="$appointment->status" class="!text-xs" />
                </div>

                <dl class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <dt class="text-[11px] font-semibold tracking-wider text-slate-400 uppercase">Phone</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800">
                            <a href="tel:{{ preg_replace('/\s+/', '', $appointment->phone) }}" class="hover:text-brand-teal">
                                {{ $appointment->phone }}
                            </a>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-[11px] font-semibold tracking-wider text-slate-400 uppercase">Email</dt>
                        <dd class="mt-1 text-sm text-slate-800">
                            @if ($appointment->email)
                                <a href="mailto:{{ $appointment->email }}" class="break-all hover:text-brand-teal">{{ $appointment->email }}</a>
                            @else
                                <span class="text-slate-400">Not provided</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-[11px] font-semibold tracking-wider text-slate-400 uppercase">Specialty requested</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-800">{{ $appointment->specialty ?? 'Not specified' }}</dd>
                    </div>

                    <div>
                        <dt class="text-[11px] font-semibold tracking-wider text-slate-400 uppercase">Specialist requested</dt>
                        <dd class="mt-1 text-sm text-slate-800">
                            {{ $appointment->specialist?->name ?? 'Any available specialist' }}
                            @if ($appointment->specialist?->role)
                                <p class="text-xs text-slate-500">{{ $appointment->specialist->role }}</p>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-[11px] font-semibold tracking-wider text-slate-400 uppercase">Referral status</dt>
                        <dd class="mt-1 text-sm text-slate-800">{{ $appointment->referral_label }}</dd>
                    </div>

                    <div>
                        <dt class="text-[11px] font-semibold tracking-wider text-slate-400 uppercase">Preferred appointment</dt>
                        <dd class="mt-1 text-sm text-slate-800">
                            {{ $appointment->preferred_date?->format('l j F Y') ?? 'No date given' }}
                            @if ($appointment->preferred_time)
                                <p class="text-xs text-slate-500">{{ $appointment->preferred_time }}</p>
                            @endif
                        </dd>
                    </div>
                </dl>

                @if ($appointment->notes)
                    <div class="mt-6 border-t border-slate-100 pt-5">
                        <dt class="text-[11px] font-semibold tracking-wider text-slate-400 uppercase">Patient notes</dt>
                        <p class="mt-2 text-sm leading-relaxed whitespace-pre-line text-slate-700">{{ $appointment->notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Internal workflow --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-editorial text-lg font-bold text-brand-teal">Update request</h2>
                <p class="text-xs text-slate-500">Track where this request sits and leave notes for the reception team.</p>

                <form method="POST" action="{{ route('admin.appointments.update', $appointment) }}" class="mt-5 space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="status" class="mb-1 block text-xs font-semibold text-slate-700">Status</label>
                            <select id="status" name="status" required
                                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-800 transition focus:border-transparent focus:ring-2 focus:ring-brand-teal focus:outline-none">
                                @foreach (\App\Models\Appointment::STATUSES as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', $appointment->status) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact-links" class="mb-1 block text-xs font-semibold text-slate-700">Contact the patient</label>
                            <div class="flex gap-2">
                                <a href="tel:{{ preg_replace('/\s+/', '', $appointment->phone) }}"
                                   class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg border border-slate-200 px-3 py-2.5 text-xs font-semibold text-brand-teal transition-colors hover:bg-slate-50">
                                    Call
                                </a>
                                @if ($appointment->email)
                                    <a href="mailto:{{ $appointment->email }}"
                                       class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg border border-slate-200 px-3 py-2.5 text-xs font-semibold text-brand-teal transition-colors hover:bg-slate-50">
                                        Email
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="admin-notes" class="mb-1 block text-xs font-semibold text-slate-700">Internal notes</label>
                        <textarea id="admin-notes" name="admin_notes" rows="4"
                                  placeholder="Outcome of the call, referral chase-ups, rescheduling notes…"
                                  class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 transition focus:border-transparent focus:ring-2 focus:ring-brand-teal focus:outline-none @error('admin_notes') border-red-400 @enderror">{{ old('admin_notes', $appointment->admin_notes) }}</textarea>
                        <p class="mt-1 text-[11px] text-slate-400">Only visible to clinic staff — never shown on the public website.</p>
                        @error('admin_notes')
                            <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between gap-3 border-t border-slate-100 pt-5">
                        <button type="submit"
                                class="rounded-lg bg-brand-teal px-5 py-2.5 text-xs font-semibold text-white shadow-sm transition-all hover:bg-brand-dark active:scale-[0.98]">
                            Save changes
                        </button>

                        @if ($appointment->updated_at->gt($appointment->created_at))
                            <p class="text-[11px] text-slate-400">Last updated {{ $appointment->updated_at->diffForHumans() }}</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="font-editorial text-base font-bold text-brand-teal">Timeline</h3>

                <ol class="mt-4 space-y-4 text-xs">
                    <li class="flex gap-3">
                        <span class="mt-1 h-2 w-2 flex-shrink-0 rounded-full bg-brand-accent"></span>
                        <div>
                            <p class="font-semibold text-slate-700">Request received</p>
                            <p class="text-slate-500">{{ $appointment->created_at->format('j M Y, g:ia') }}</p>
                        </div>
                    </li>

                    <li class="flex gap-3">
                        <span class="mt-1 h-2 w-2 flex-shrink-0 rounded-full {{ $appointment->status === \App\Models\Appointment::STATUS_NEW ? 'bg-slate-200' : 'bg-brand-accent' }}"></span>
                        <div>
                            <p class="font-semibold text-slate-700">Currently {{ $appointment->status_label }}</p>
                            <p class="text-slate-500">Updated {{ $appointment->updated_at->diffForHumans() }}</p>
                        </div>
                    </li>
                </ol>
            </div>

            <div class="rounded-2xl border border-red-100 bg-red-50/40 p-6">
                <h3 class="font-editorial text-base font-bold text-red-800">Danger zone</h3>
                <p class="mt-1 text-xs leading-relaxed text-red-700">
                    Permanently delete this booking request. This cannot be undone.
                </p>

                <form method="POST" action="{{ route('admin.appointments.destroy', $appointment) }}" class="mt-4"
                      onsubmit="return confirm('Permanently delete the request from {{ $appointment->full_name }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full rounded-lg border border-red-300 bg-white px-4 py-2.5 text-xs font-semibold text-red-700 transition-colors hover:bg-red-100">
                        Delete request
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
