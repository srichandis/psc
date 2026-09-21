@php
    $input = 'w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 transition focus:border-transparent focus:ring-2 focus:ring-brand-teal focus:outline-none';
    $labelClass = 'mb-1 block text-xs font-semibold text-slate-700';
    $hintClass = 'mt-1 text-[11px] text-slate-400';
    $errorClass = 'mt-1 text-[11px] text-red-600';
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6" x-data="{ icon: @js(old('icon', $specialty->icon ?: 'brain')) }">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    {{-- Name --}}
    <div>
        <label for="name" class="{{ $labelClass }}">Specialty name <span class="text-red-500">*</span></label>
        <input id="name" type="text" name="name" required value="{{ old('name', $specialty->name) }}"
               placeholder="e.g. Neurology"
               class="{{ $input }} @error('name') border-red-400 @enderror">
        <p class="{{ $hintClass }}">The web address is generated automatically, e.g. <span class="font-mono">neurology</span>.</p>
        @error('name')
            <p class="{{ $errorClass }}">{{ $message }}</p>
        @enderror
    </div>

    {{-- Icon --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="icon" class="{{ $labelClass }}">Card icon <span class="text-red-500">*</span></label>
            <select id="icon" name="icon" x-model="icon" required class="{{ $input }} bg-white @error('icon') border-red-400 @enderror">
                @foreach (\App\Models\Specialty::ICONS as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            @error('icon')
                <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <span class="{{ $labelClass }}">Preview</span>
            <div class="flex h-[46px] items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 px-4 text-brand-accent">
                @foreach (\App\Models\Specialty::ICONS as $value => $label)
                    <span x-show="icon === @js($value)" x-cloak class="flex items-center gap-2.5">
                        <x-specialty-icon :name="$value" class="h-7 w-7" />
                        <span class="text-xs font-semibold text-slate-600">{{ $label }}</span>
                    </span>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Short description --}}
    <div>
        <label for="short_description" class="{{ $labelClass }}">Short description <span class="text-red-500">*</span></label>
        <textarea id="short_description" name="short_description" rows="2" required
                  placeholder="Shown on the homepage specialty card and in the site navigation."
                  class="{{ $input }} @error('short_description') border-red-400 @enderror">{{ old('short_description', $specialty->short_description) }}</textarea>
        @error('short_description')
            <p class="{{ $errorClass }}">{{ $message }}</p>
        @enderror
    </div>

    {{-- Full description --}}
    <div>
        <label for="full_description" class="{{ $labelClass }}">Department overview</label>
        <textarea id="full_description" name="full_description" rows="4"
                  placeholder="Shown in the department detail modal on the homepage."
                  class="{{ $input }} @error('full_description') border-red-400 @enderror">{{ old('full_description', $specialty->full_description) }}</textarea>
        @error('full_description')
            <p class="{{ $errorClass }}">{{ $message }}</p>
        @enderror
    </div>

    {{-- Conditions + services --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        <div>
            <label for="conditions" class="{{ $labelClass }}">Key conditions managed</label>
            <textarea id="conditions" name="conditions" rows="6"
                      placeholder="One condition per line, e.g.&#10;Stroke &amp; TIA&#10;Cognitive Impairment &amp; Dementia"
                      class="{{ $input }} @error('conditions') border-red-400 @enderror">{{ old('conditions', implode("\n", $specialty->conditions ?? [])) }}</textarea>
            <p class="{{ $hintClass }}">One item per line.</p>
            @error('conditions')
                <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="diagnostic_services" class="{{ $labelClass }}">Diagnostic &amp; clinical services</label>
            <textarea id="diagnostic_services" name="diagnostic_services" rows="6"
                      placeholder="One service per line, e.g.&#10;Cognitive Screening&#10;Neurovascular Workup"
                      class="{{ $input }} @error('diagnostic_services') border-red-400 @enderror">{{ old('diagnostic_services', implode("\n", $specialty->diagnostic_services ?? [])) }}</textarea>
            <p class="{{ $hintClass }}">One item per line.</p>
            @error('diagnostic_services')
                <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Order + visibility --}}
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="sort_order" class="{{ $labelClass }}">Display order</label>
            <input id="sort_order" type="number" name="sort_order" min="0" max="9999"
                   value="{{ old('sort_order', $specialty->sort_order ?? '') }}" placeholder="Auto"
                   class="{{ $input }} @error('sort_order') border-red-400 @enderror">
            <p class="{{ $hintClass }}">Lower numbers appear first on the homepage.</p>
            @error('sort_order')
                <p class="{{ $errorClass }}">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-start pt-6">
            <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-slate-200 p-3.5 transition-colors hover:bg-slate-50">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $specialty->is_active ?? true))
                       class="mt-0.5 rounded border-slate-300 text-brand-teal focus:ring-brand-teal">
                <span>
                    <span class="block text-xs font-semibold text-slate-800">Show on the website</span>
                    <span class="mt-0.5 block text-[11px] text-slate-500">Uncheck to hide this department from the homepage.</span>
                </span>
            </label>
        </div>
    </div>

    {{-- Actions --}}
    <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
        <a href="{{ route('admin.specialties.index') }}"
           class="rounded-lg px-4 py-2.5 text-xs font-semibold text-slate-600 transition-colors hover:bg-slate-100">
            Cancel
        </a>

        <button type="submit"
                class="rounded-lg bg-brand-teal px-5 py-2.5 text-xs font-semibold text-white shadow-sm transition-all hover:bg-brand-dark active:scale-[0.98]">
            {{ $submitLabel }}
        </button>
    </div>
</form>
